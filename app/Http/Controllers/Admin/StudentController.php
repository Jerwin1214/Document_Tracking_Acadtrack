<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index()
    {
        return view('pages.students.dashboard');
    }

    public function create()
    {
        return view('pages.admin.student.add');
    }

    public function store(Request $request)
    {
        // Validate user inputs
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'std_nic' => ['nullable', 'string', 'max:12'], // Nullable to allow empty input
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Ensure email is unique
            'password' => ['required', 'string', 'min:5'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        // Use a transaction to ensure all data is stored correctly
        DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3, // Assuming role_id 3 is for students
                'created_at' => now(),
            ]);

            // Create guardian
            $guardian = Guardian::create([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
                'created_at' => now(),
            ]);

            // Create student
            Student::create([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? "",
                'created_at' => now(),
                'user_id' => $user->id,
                'guardian_id' => $guardian->id,
            ]);
        });

        return redirect('/admin/students/show')->with('success', 'Student added successfully');
    }


    public function showAllStudents()
    {
        return view('pages.admin.student.index', [
            'students' => Student::select(['id', 'first_name', 'last_name'])->paginate(20)
        ]);
    }

    public function show(Student $student)
    {
        return view('pages.admin.student.show', ['student' => $student]);
    }

    public function edit(Student $student)
    {
        return view('pages.admin.student.edit', ['student' => $student]);
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'std_nic' => ['string', 'max:12'],
            'dob' => ['required', 'date'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        // update the guardian information
        $student->guardian->update([
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'updated_at' => now(),
        ]);

        // update the student information
        $student->update([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->std_nic ?? "",
            'updated_at' => now(),
        ]);

        return redirect('/admin/students/show')->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        return redirect('/admin/students/show')->with('success', 'Student deleted successfully');
    }
}
