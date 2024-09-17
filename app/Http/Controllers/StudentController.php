<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        // TODO: implement the index method
        return view('pages.students.dashboard');
    }

    public function create()
    {
        // TODO: implement the create method
    }

    public function store()
    {
        // TODO: implement the store method
    }

    public function show(Student $student)
    {
        // TODO: implement the show method
    }

    public function edit(Student $student)
    {
        // TODO: implement the edit method
    }

    public function update(Student $student)
    {
        // TODO: implement the update method
    }

    public function destroy(Student $student)
    {
        // TODO: implement the destroy method
    }
}
