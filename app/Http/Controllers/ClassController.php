<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
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
        // TODO: implement the store method
    }

    public function show(Classes $class)
    {
        return view('pages.admin.class.show', ['class' => $class]);
    }

    public function edit(Classes $class)
    {
        return view('pages.admin.class.edit', ['class' => $class]);
    }

    public function update(Request $request, Classes $class)
    {
        // TODO: implement the update method
    }

    public function destroy(Classes $class)
    {
        // TODO: implement the destroy method
    }

    public function assignStudentsView(Classes $class)
    {
        // return view('pages.admin.class.assign-students', ['class' => $class, 'students' => Student::all()]);
        dd("HI");
    }

    public function assignStudents(Request $request, Classes $class)
    {
        // TODO: implement the assignStudents method
    }
}
