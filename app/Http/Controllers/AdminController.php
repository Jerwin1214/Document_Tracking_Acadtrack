<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.dashboard');
    }

    public function showAllStudents()
    {
        $students = Student::all();
        return view('pages.admin.student.index', ['students' => $students]);
    }

    public function create()
    {
        return view('pages.admin.student.add');
    }

    public function store(Request $request)
    {
        // validate user inputs
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'nic' => ['string', 'max:12'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:5'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        // create a new student
        // store data into the users table
        $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 3,
            'created_at' => now(),
        ]);

        // store data into the students table
        $student = Student::create([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->nic ?? "",
            'created_at' => now(),
            'user_id' => $user->id,
        ]);

        // store data into the guardians table
        Guardian::create([
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'created_at' => now(),
            'student_id' => $student->id,
        ]);

        return redirect('/admin/students/show')->with('success', 'Student added successfully');
    }

    public function show(Admin $admin)
    {
        // TODO: implement the show method
    }

    public function edit(Admin $admin)
    {
        // TODO: implement the edit method
    }

    public function update(Admin $admin)
    {
        // TODO: implement the update method
    }

    public function destroy(Admin $admin)
    {
        // TODO: implement the destroy method
    }
}
