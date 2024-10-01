<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('classes')
            ->leftJoin('class_student', 'classes.id', '=', 'class_student.class_id')
            ->select('classes.id', 'classes.grade_id', 'classes.teacher_id', 'classes.subject_id', 'classes.name', 'classes.year', DB::raw('COUNT(class_student.student_id) as students_count'))
            ->groupBy('classes.id', 'classes.grade_id', 'classes.teacher_id', 'classes.subject_id', 'classes.name', 'classes.year')
            ->get();

        return view('pages.admin.class.index', ['classes' => $classes]);
    }

    public function create()
    {
        return view('pages.admin.class.add', ['teachers' => Teacher::all(), 'grades' => Grade::all(), 'subjects' => Subject::all()]);
    }

    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'grade' => ['required'],
            'class_name' => ['required', 'string'],
            'subject' => ['required'],
            'teacher' => ['required'],
            'year' => ['required', 'numeric'],
        ]);

        // create the class
        Classes::create([
            'grade_id' => $request->grade,
            'name' => $request->class_name,
            'subject_id' => $request->subject,
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
        return view('pages.admin.class.edit', ['class' => $class, 'grades' => Grade::all(), 'subjects' => Subject::all(), 'teachers' => Teacher::all(),]);
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
//        dd($request->students);
        foreach ($request->students as $student) {
            DB::table('class_student')->insert([
                'class_id' => $class->id,
                'student_id' => $student,
                'created_at' => now(),
            ]);
        }
        // redirect to the show classes page with a success message
        return redirect('/admin/class/show')->with('success', 'Students assigned to class successfully!');
    }
}
