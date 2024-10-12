<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherStudentController extends Controller
{
    protected $teacherId;

    public function __construct()
    {
        // Get the authenticated teacher's ID once to avoid multiple queries
        $this->teacherId = Teacher::where('user_id', auth()->id())->first()->id;
    }

    public function index()
    {
        return view('pages.teachers.students.index');
    }

    public function create()
    {
        return view('pages.teachers.students.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'std_first_name' => 'required|string|max:30',
            'std_last_name' => 'required|string|max:50',
            'gender' => 'required|string',
            'std_nic' => 'nullable|string|max:12',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:5',
            'initials' => 'required|string|max:10',
            'g_first_name' => 'required|string|max:30',
            'g_last_name' => 'required|string|max:50',
            'g_nic' => 'required|string|max:12',
            'g_phone' => 'required|string|max:10',
        ]);

        DB::transaction(function () use ($request) {
            // Create user, guardian, and student records in a transaction
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3,
            ]);

            $guardian = Guardian::create([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
            ]);

            $student = Student::create([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? '',
                'user_id' => $user->id,
                'guardian_id' => $guardian->id,
            ]);

            // Assign student to the class
            $classId = DB::table('classes')->where('teacher_id', $this->teacherId)->first()->id;
            DB::table('class_student')->insert([
                'class_id' => $classId,
                'student_id' => $student->id,
            ]);
        });

        return redirect('/teacher/students/show')->with('success', 'Student added successfully');
    }

    public function showAllStudents()
    {
        // Fetch students with their classes taught by the current teacher, eager load only relevant data
        $studentsOfTeacher = Student::whereHas('classes', function ($query) {
            $query->where('teacher_id', $this->teacherId);
        })
            ->with(['classes' => function ($query) {
                $query->where('teacher_id', $this->teacherId);
            }])
            ->distinct()
            ->paginate(20);

        return view('pages.teachers.students.index', ['students' => $studentsOfTeacher]);
    }

    public function show(Student $student)
    {
        // Eager load the guardian to avoid extra queries
        $student->load('guardian');

        // Get the subjects that are specifically assigned to the student
        $subjects = DB::table('subjects')
            ->join('student_subjects', 'subjects.id', '=', 'student_subjects.subject_id')
            ->where('student_subjects.student_id', $student->id)
            ->select('subjects.code', 'subjects.name')
            ->get();

        return view('pages.teachers.students.show', [
            'student' => $student,
            'subjects' => $subjects,
        ]);
    }

    public function edit(Student $student)
    {
        // Eager load guardian to avoid extra queries
        $student->load('guardian');
        return view('pages.teachers.students.edit', ['student' => $student]);
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => 'required|string|max:30',
            'std_last_name' => 'required|string|max:50',
            'gender' => 'required|string|max:5',
            'std_nic' => 'nullable|string|max:12',
            'dob' => 'required|date',
            'initials' => 'required|string|max:10',
            'g_first_name' => 'required|string|max:30',
            'g_last_name' => 'required|string|max:50',
            'g_nic' => 'required|string|max:12',
            'g_phone' => 'required|string|max:10',
        ]);

        DB::transaction(function () use ($student, $request) {
            // Update guardian details
            $student->guardian->update([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
            ]);

            // Update student details
            $student->update([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? '',
            ]);
        });

        return redirect('/teacher/students/show')->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        // Deleting student and associated user in one go
        DB::transaction(function () use ($student) {
            $student->user()->delete();
            $student->delete();
        });

        return redirect('/teacher/students/show')->with('success', 'Student deleted successfully');
    }

    public function assignSubjectsView(Student $student)
    {
        // Get the class of the student (assuming a student is enrolled in one class at a time)
        $class = $student->classes()->first();

        // Retrieve the subject stream ID from the class
        $subjectStreamId = $class->subject_stream_id;

        // Get all subjects for the student's subject stream
        $subjects = DB::table('subjects')
            ->join('subject_stream_subject', 'subjects.id', '=', 'subject_stream_subject.subject_id')
            ->where('subject_stream_subject.subject_stream_id', $subjectStreamId)
            ->select('subjects.*')
            ->get();

        // Get IDs of subjects already assigned to the student
        $assignedSubjectIds = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->pluck('subject_id')
            ->toArray();

        return view('pages.teachers.students.assign-subjects', [
            'student' => $student,
            'subjects' => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds, // Pass assigned subject IDs to the view
        ]);
    }

    public function assignSubjects(Request $request, Student $student)
    {
        // validate the inputs
        $request->validate([
            'subjects' => ['required'],
        ]);
        // Retrieve the IDs of the subjects selected by the user in the form
        $selectedSubjectIds = $request->input('subjects', []);

        // Get the IDs of subjects already assigned to the student
        $existingSubjectIds = DB::table('student_subjects')
            ->where('student_id', $student->id)
            ->pluck('subject_id')
            ->toArray();

        // Find new subjects to insert (selected subjects that aren’t in the existing subjects)
        $subjectsToInsert = array_diff($selectedSubjectIds, $existingSubjectIds);

        // Find subjects to delete (existing subjects that aren’t in the selected subjects)
        $subjectsToDelete = array_diff($existingSubjectIds, $selectedSubjectIds);

        // Start a database transaction to ensure data consistency
        DB::transaction(function () use ($subjectsToInsert, $subjectsToDelete, $student) {
            // Insert new subjects
            foreach ($subjectsToInsert as $subjectId) {
                DB::table('student_subjects')->insert([
                    'student_id' => $student->id,
                    'subject_id' => $subjectId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Delete removed subjects
            DB::table('student_subjects')
                ->where('student_id', $student->id)
                ->whereIn('subject_id', $subjectsToDelete)
                ->delete();
        });

        // Redirect back with a success message
        return redirect('/teacher/students/show')->with('success', 'Subjects updated successfully!');
    }
}
