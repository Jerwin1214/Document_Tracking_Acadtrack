<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\SubjectStream;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('classes')
            ->leftJoin('class_student', 'classes.id', '=', 'class_student.class_id')
            ->leftJoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
            ->leftJoin('grades', 'classes.grade_id', '=', 'grades.id')
            ->leftJoin('subject_streams', 'classes.subject_stream_id', '=', 'subject_streams.id')
            ->select(
                'classes.id',
                'classes.grade_id',
                'classes.teacher_id',
                'classes.subject_stream_id',
                'classes.name',
                'classes.year',
                'teachers.first_name as teacher_first_name',
                'teachers.last_name as teacher_last_name',
                'grades.name as grade_name',
                'subject_streams.stream_name as subject_stream_name',
                DB::raw('COUNT(class_student.student_id) as students_count')
            )
            ->groupBy(
                'classes.id',
                'classes.grade_id',
                'classes.teacher_id',
                'classes.subject_stream_id',
                'classes.name',
                'classes.year',
                'teachers.first_name',
                'teachers.last_name',
                'grades.name',
                'subject_streams.stream_name'
            )
            ->paginate(20);

        return view('pages.admin.class.index', ['classes' => $classes]);
    }

    public function create()
    {
        // Cache the grades for 10 minutes
        $grades = Cache::remember('grades', 600, function () {
            return Grade::select(['id', 'name'])->get();
        });

        // Cache the teachers for 10 minutes
        $teachers = Cache::remember('teachers', 600, function () {
            return Teacher::select(['id', 'salutation', 'first_name', 'last_name'])->get();
        });

        // Cache the subjects for 10 minutes
        $streams = SubjectStream::select(['id', 'stream_name'])->get();

        return view('pages.admin.class.add', compact('teachers', 'grades', 'streams'));
    }

    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'grade' => ['required'],
            'class_name' => ['required', 'string'],
            'subject_stream' => ['required'],
            'teacher' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        // create the class
        Classes::create([
            'grade_id' => $request->grade,
            'name' => $request->class_name,
            'subject_stream_id' => $request->subject_stream,
            'teacher_id' => $request->teacher,
            'year' => $request->year,
        ]);

        // redirect to the all classes page
        return redirect('/admin/class/show')->with('success', 'Class created successfully');
    }

    public function show(Classes $class)
    {
        return view('pages.admin.class.show', ['class' => $class]);
    }

    public function edit(Classes $class)
    {
        // Cache the grades for 10 minutes
        $grades = Cache::remember('grades', 600, function () {
            return Grade::select(['id', 'name'])->get();
        });

        // Cache the teachers for 10 minutes
        $teachers = Cache::remember('teachers', 600, function () {
            return Teacher::select(['id', 'salutation', 'first_name', 'last_name'])->get();
        });

        // Cache the subjects for 10 minutes
        $subjects = Cache::remember('subjects', 600, function () {
            return Subject::select(['id', 'name'])->get();
        });
        return view('pages.admin.class.edit', ['class' => $class, 'grades' => $grades, 'subjects' => $subjects, 'teachers' => $teachers]);
    }

    public function update(Request $request, Classes $class)
    {
        // validate the user input
        $request->validate([
            'grade' => ['required'],
            'class_name' => ['required', 'string'],
            'subject' => ['required'],
            'teacher' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        $class->update([
            'grade_id' => $request->grade,
            'name' => $request->class_name,
            'subject_id' => $request->subject,
            'teacher_id' => $request->teacher,
            'year' => $request->year,
        ]);

        // redirect to the show classes page with a success message
        return redirect('/admin/class/show')->with('success', 'Class details updated successfully!');
    }

    public function destroy(Classes $class)
    {
        // TODO: implement the destroy method
        $class->delete();
        return redirect('/admin/class/show')->with('success', 'Class deleted successfully');
    }

    public function assignStudentsView(Classes $class)
    {
        $unassignedStudents = DB::table('students')
            ->leftJoin('class_student', 'students.id', '=', 'class_student.student_id')
            ->whereNull('class_student.class_id')
            ->select('students.*')
            ->get();

        return view('pages.admin.class.assign-students', [
            'class' => $class,
            'students' => $unassignedStudents,
        ]);
    }

    public function assignStudents(Request $request, Classes $class)
    {
        // Assign students to the class
        foreach ($request->students as $studentId) {
            DB::table('class_student')->insert([
                'class_id' => $class->id,
                'student_id' => $studentId,
                'created_at' => now(),
            ]);

            // Get the subjects assigned to the class's subject stream
            $subjects = DB::table('subject_stream_subject')
                ->where('subject_stream_id', $class->subject_stream_id)
                ->pluck('subject_id'); // Retrieve only subject IDs

            // Insert each subject for this student into student_subjects table
            foreach ($subjects as $subjectId) {
                DB::table('student_subjects')->insert([
                    'subject_id' => $subjectId,
                    'student_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Redirect with a success message
        return redirect('/admin/class/show')->with('success', 'Students and their subjects assigned to class successfully!');
    }
}
