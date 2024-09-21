<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function store(Request $request)
    {
        // validate the teacher details
        $request->validate([
            'salutation' => ['required', 'string', 'max:5'],
            'initials' => ['required', 'string', 'max:15'],
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'nic' => ['required', 'string', 'max:12'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:5'],
        ]);

        // store the teacher's credentials in users table
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
            'created_at' => now(),
        ]);

        // store the teacher's details in teachers table
        Teacher::create([
            'salutation' => $request->salutation,
            'initials' => $request->initials,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nic' => $request->nic,
            'dob' => $request->dob,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        // redirect to the teachers index page with a success message
        return redirect('/admin/teachers/show')->with('success', 'Teacher added successfully');
    }

    public function showAllTeachers()
    {
        $teachers = Teacher::all();
        return view('pages.admin.teacher.index', ['teachers' => $teachers]);
    }
    public function show(Teacher $teacher)
    {
        return view('pages.admin.teacher.show', ['teacher' => $teacher]);
    }

    public function edit(Teacher $teacher)
    {
        return view('pages.admin.teacher.edit', ['teacher' => $teacher]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        // TODO: implement the update method
        $request->validate([
            'salutation' => ['required', 'string', 'max:5'],
            'initials' => ['required', 'string', 'max:15'],
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'nic' => ['required', 'string', 'max:12'],
            'dob' => ['required', 'date'],
        ]);

        $teacher->update([
            'salutation' => $request->salutation,
            'initials' => $request->initials,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'nic' => $request->nic,
            'dob' => $request->dob,
        ]);

        return redirect('/admin/teachers/show')->with('success', 'Teacher updated successfully');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user()->delete();
        return redirect('/admin/teachers/show')->with('success', 'Teacher deleted successfully');
    }
}
