<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store()
    {
        // Validate the request data
        $attrs = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (!Auth::attempt($attrs)) {
            throw ValidationException::withMessages([
                // 'email' => 'Your provided credentials could not be verified.'
                'email' => 'Invalid Email',
                'password' => 'Invalid Password'
            ]);
        }

        // Regenerate session token for security
        request()->session()->regenerate();

        // Retrieve the user's role
        $role = auth()->user()->role->name ?? null;

        // Redirect based on role
        switch ($role) {
            case 'Admin':
                return redirect('/admin/dashboard')->with('greeting', 'Welcome back, Admin!');
            case 'Student':
                return redirect('/student/dashboard')->with('greeting', 'Welcome back, Student!');
            case 'Teacher':
                return redirect('/teacher/dashboard')->with('greeting', 'Welcome back, Teacher!');
            default:
                // Handle if the user doesn't have a role
                auth()->logout();
                return redirect('/')->withErrors([
                    'role' => 'User does not have a valid role.'
                ]);
        }
    }

    public function destroy()
    {
        // logout functionality
        auth()->logout();
        // invalidate the user
        request()->session()->invalidate();
        // regenerte the CSRF token
        request()->session()->regenerateToken();
        // redirect to the login page
        return redirect('/');
    }
}
