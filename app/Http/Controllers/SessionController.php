<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function create()
    {
        $role = session('user_role');

        if (!$role) {
            return redirect()->route('select.role')->withErrors([
                'role' => 'Please select a role first.'
            ]);
        }

        return view('auth.login', compact('role'));
    }

    public function selectRole($role)
    {
        $validRoles = ['admin', 'teacher', 'student'];

        if (!in_array(strtolower($role), $validRoles)) {
            return redirect('/')->withErrors(['role' => 'Invalid role selected.']);
        }

        session(['user_role' => strtolower($role)]);

        return redirect()->route('login');
    }

    public function store(Request $request)
    {
        $role = session('user_role');

        if (!$role) {
            return redirect()->route('select.role')->withErrors([
                'role' => 'Please select a role first.'
            ]);
        }

        // Validation rules
        $rules = [
            'user_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];

        $credentials = $request->validate($rules);

        // Attempt login using user_id and password
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'user_id' => 'Invalid User ID or Password',
            ]);
        }

        $request->session()->regenerate();

        $user = auth()->user();

        // Correct role mapping according to your seeded users
        $roleMap = [
            'admin' => 1,
            'student' => 2,
            'teacher' => 3,
        ];

        // Check if user's role matches selected login role
        if ((int)$user->role_id !== $roleMap[$role]) {
            Auth::logout();
            return back()->withErrors([
                'user_id' => "You are not authorized to log in as " . ucfirst($role) . ".",
            ]);
        }

        // Redirect to dashboard
        return match ($role) {
            'admin' => redirect()->route('admin.documents.dashboard')
                                   ->with('greeting', 'Welcome back, Admin!'),
            'teacher' => redirect()->route('teacher.dashboard')
                                   ->with('greeting', 'Welcome back, Teacher!'),
            'student' => redirect()->route('student.dashboard')
                                   ->with('greeting', 'Welcome back, Student!'),
            default => redirect('/')->withErrors(['role' => 'User does not have a valid role.']),
        };
    }

    public function destroy()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
