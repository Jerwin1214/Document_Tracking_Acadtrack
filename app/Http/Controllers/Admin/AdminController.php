<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $counts = DB::table('students')
            ->selectRaw('(SELECT COUNT(*) FROM students INNER JOIN users ON (students.user_id=users.id) WHERE users.is_active=1) as students_count')
            ->selectRaw('(SELECT COUNT(*) FROM teachers INNER JOIN users ON (teachers.user_id=users.id) WHERE users.is_active=1) as teachers_count')
            ->selectRaw('(SELECT COUNT(*) FROM subjects) as subjects_count')
            ->first();

        return view('pages.admin.dashboard', compact('counts'));
    }

    public function create()
    {
        // TODO: implement the create method
    }

    public function store(Request $request)
    {
        // TODO: implement the store method
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

    public function showMessage()
    {
        return view('pages.admin.messages.show');
    }
}
