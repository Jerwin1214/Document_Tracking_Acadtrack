<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        return view('pages.admin.class.index', ['classes' => Classes::all()]);
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
         return view('pages.admin.class.assign-students', ['class' => $class, 'students' => Student::all()]);
    }

    public function assignStudents(Request $request, Classes $class)
    {
        dd($request->all());
    }
}
