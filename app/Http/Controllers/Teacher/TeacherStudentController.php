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

        // Get the class of the student (assuming a student is enrolled in one class at a time)
        $class = $student->classes()->first(); // Adjust if students can belong to multiple classes

        // Retrieve the subject stream ID from the class
        $subjectStreamId = $class->subject_stream_id;

        // Get all subjects for the student's subject stream
        $subjects = DB::table('subjects')
            ->join('subject_stream_subject', 'subjects.id', '=', 'subject_stream_subject.subject_id')
            ->where('subject_stream_subject.subject_stream_id', $subjectStreamId)
            ->select('subjects.*')
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
}
