<?php

namespace App\Http\Controllers;

use App\Models\Teacher;

class TeacherController extends Controller
{
    public function index()
    {
        // TODO: implement the index method
        return view('pages.teachers.dashboard');
    }

    public function create()
    {
        return view('pages.admin.teacher.add');
    }

    public function store()
    {
        // TODO: implement the store method
    }

    public function showAllTeachers()
    {
        $teachers = Teacher::all();
        return view('pages.admin.teacher.index', ['teachers' => $teachers]);
    }
    public function show(Teacher $teacher)
    {
        // TODO: implement the show method
    }

    public function edit(Teacher $teacher)
    {
        // TODO: implement the edit method
    }

    public function update(Teacher $teacher)
    {
        // TODO: implement the update method
    }

    public function destroy(Teacher $teacher)
    {
        // TODO: implement the destroy method
    }
}
