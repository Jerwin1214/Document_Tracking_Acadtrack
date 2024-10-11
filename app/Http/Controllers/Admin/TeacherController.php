<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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
        return view('pages.admin.teacher.index', [
            'teachers' => Teacher::with(['user', 'subjects']) // eager load the user
                ->select(['id', 'first_name', 'last_name', 'user_id'])
                ->paginate(20)
        ]);
    }

    public function show(Teacher $teacher)
    {
        return view('pages.admin.teacher.show', ['teacher' => $teacher]);
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Cache::remember('subjects_list', 60, function () {
            return Subject::get();
        });
        return view('pages.admin.teacher.edit', ['teacher' => $teacher, 'subjects' => $subjects]);
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

    public function assignClassView(Teacher $teacher)
    {
        // $classes = Cache::remember('classes_list', 60, function () {
        //     return Classes::all();
        // });
        return redirect('/admin/teachers/show')->with('info', 'This feature is not implemented yet!');
    }

    public function assignClasses(Request $request, Teacher $teacher)
    {
        // TODO: implement the assignClasses method
    }
}
