<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
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

        $user = auth()->user();

        // Flag to track if email changed
        $emailChanged = false;

        // Check if email has changed
        if ($request->email != $user->email) {
            $request->validate(['email' => ['unique:users,email']]);
            $user->update(['email' => $request->email]);
            $emailChanged = true;
        }

        // Check if password change is requested
        if ($request->old_password && $request->password) {
            if (!password_verify($request->old_password, $user->password)) {
                return back()->with('error', 'Old password is incorrect');
            }

            $user->update(['password' => bcrypt($request->password)]);
        }

        // If email changed, log the user out
        if ($emailChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'Email and password changed. Please login again.');
        }

        return redirect('/')->with('success', 'Password changed successfully');
    }

    public function showMessages()
    {
        return view('pages.admin.messages.index');
    }

    public function showMessage() {
        return view('pages.admin.messages.show');
    }
}
