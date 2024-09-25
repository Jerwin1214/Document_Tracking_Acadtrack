<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        return view('pages.admin.dashboard', ['students' => Student::all(), 'teachers' => Teacher::all(), 'subjects' => Subject::all()]);
    }

    public function create()
    {
        // TODO: implement the create method
    }

    public function store(Request $request) {}

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

    public function showProfile()
    {
        return view('pages.admin.profile');
    }

    public function showSettings()
    {
        return view('pages.admin.settings');
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'string', 'max:255'],
            'old_password' => ['nullable', 'string', 'min:8'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->email != auth()->user()->email) {
            // email has changed
            $request->validate([
                'email' => ['unique:users,email'],
            ]);
            // change the email and logout with a message
            User::where('id', auth()->user()->id)
                ->update(['email' => $request->email]);
            // logout functionality
            Auth::logout();
            // invalidate the user
            request()->session()->invalidate();
            // regenerte the CSRF token
            request()->session()->regenerateToken();
            // redirect to the login page
            return redirect('/')->with('success', 'Email changed successfully. Please login again');
        }
        return back()->with('info', 'Changes not saved');
    }
}
