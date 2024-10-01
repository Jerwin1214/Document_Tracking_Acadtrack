<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        if (Auth::user()->role->name == 'Admin') {
            return view('pages.admin.students.index');
        } elseif (Auth::user()->role->name == 'Teacher') {
            return view('pages.teachers.students.index');
        }
    }

    public function create()
    {
        if (Auth::user()->role->name == 'Admin') {
            return view('pages.admin.student.add');
        } elseif (Auth::user()->role->name == 'Teacher') {
            return view('pages.teachers.students.add');
        }
    }

    public function store(Request $request)
    {
        // validate user inputs
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'std_nic' => ['string', 'max:12'],
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

        // store data into the guardians table
        $guardian = Guardian::create([
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'created_at' => now(),
        ]);

        // store data into the students table
        $student = Student::create([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->std_nic ?? "",
            'created_at' => now(),
            'user_id' => $user->id,
            'guardian_id' => $guardian->id,
        ]);

        if (Auth::user()->role->name == 'Admin') {
            return redirect('/admin/students/show')->with('success', 'Student added successfully');
        } elseif (Auth::user()->role->name == 'Teacher') {
            return redirect('/teacher/students/show')->with('success', 'Student added successfully');

        }
    }

    public function showAllStudents()
    {
        $students = Student::all();
        if (Auth::user()->role->name == 'Admin') {
            return view('pages.admin.student.index', ['students' => $students]);
        } elseif (Auth::user()->role->name == 'Teacher') {
            return view('pages.teachers.students.index', ['students' => $students]);
        }
    }

    public function show(Student $student)
    {
        if (Auth::user()->role->name == 'Admin') {
            return view('pages.admin.student.show', ['student' => $student]);
        } elseif (Auth::user()->role->name == 'Teacher') {
            return view('pages.teachers.students.show', ['student' => $student]);
        }
    }

    public function edit(Student $student)
    {
        if (Auth::user()->role->name == 'Admin') {
            return view('pages.admin.student.edit', ['student' => $student]);
        } elseif (Auth::user()->role->name == 'Teacher') {
            return view('pages.teachers.student.edit', ['student' => $student]);
        }
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'std_nic' => ['string', 'max:12'],
            'dob' => ['required', 'date'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        // update the student

        // store data into the guardians table
        $student->guardian->update([
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'updated_at' => now(),
        ]);

        // store data into the students table
        $student->update([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->std_nic ?? "",
            'updated_at' => now(),
        ]);
        if (Auth::user()->role->name == 'Admin') {
            return redirect('/admin/students/show')->with('success', 'Student updated successfully');
        } elseif (Auth::user()->role->name == 'Teacher') {
            return redirect('/teacher/students/show')->with('success', 'Student updated successfully');
        }
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        if (Auth::user()->role->name == 'Admin') {
            return redirect('/admin/students/show')->with('success', 'Student deleted successfully');
        } elseif (Auth::user()->role->name == 'Teacher') {
            return redirect('/teacher/students/show')->with('success', 'Student deleted successfully');
        }
    }

    public function countStudents()
    {
        $count = Student::count();
        return response($count);
    }
}
