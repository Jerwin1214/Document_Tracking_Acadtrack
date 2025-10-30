<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Show login form based on selected role.
     */
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

    /**
     * Select user role before login.
     */
    public function selectRole($role)
    {
        $validRoles = ['admin', 'teacher', 'student'];

        if (!in_array(strtolower($role), $validRoles)) {
            return redirect('/')->withErrors(['role' => 'Invalid role selected.']);
        }

        session(['user_role' => strtolower($role)]);

        return redirect()->route('login');
    }

    /**
     * Handle login attempt.
     */
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
        if (!Auth::attempt([
            'user_id' => $credentials['user_id'],
            'password' => $credentials['password'],
            'is_active' => true, // ensure only active users can log in
        ])) {
            throw ValidationException::withMessages([
                'user_id' => 'Invalid User ID or Password',
            ]);
        }

        $request->session()->regenerate();

        $user = auth()->user();

        // Role ID mapping
        $roleMap = [
            'admin' => 1,
            'teacher' => 2,
            'student' => 3,
        ];

        // Check if user's role matches selected login role
        if (!isset($roleMap[$role]) || (int)$user->role_id !== $roleMap[$role]) {
            Auth::logout();
            return back()->withErrors([
                'user_id' => "You are not authorized to log in as " . ucfirst($role) . ".",
            ]);
        }

        // Redirect to dashboard based on role
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

    /**
     * Log out user.
     */
    public function destroy()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }
}
