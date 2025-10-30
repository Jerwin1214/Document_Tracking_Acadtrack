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
            return redirect()->route('select.role')->withErrors(['Please select a role first.']);
        }

        return view('auth.login', compact('role'));
    }

    public function selectRole($role)
    {
        $validRoles = ['admin', 'teacher', 'student'];

        if (!in_array(strtolower($role), $validRoles)) {
            return redirect('/')->withErrors(['Invalid role selected.']);
        }

        session(['user_role' => strtolower($role)]);
        return redirect()->route('login');
    }

    public function store(Request $request)
    {
        $role = session('user_role');

        $rules = [
            'user_id' => ['required'],
            'password' => ['required'],
        ];

        if ($role === 'student') {
            $rules['user_id'][] = 'regex:/^\d{4}-\d{4}$/';
        }

        $credentials = $request->validate($rules);

        // Attempt login using user_id and password on users table
        if (!Auth::attempt([
            'user_id' => $credentials['user_id'],
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'user_id' => 'Invalid User ID or Password',
            ]);
        }

        $request->session()->regenerate();

        $user = auth()->user();

        // Correct role mapping based on your roles table
        $roleMap = [
            'admin' => 1,
            'student' => 2,
            'teacher' => 3,
        ];

        // Check role matches session (cast to int for safety)
        if ((int)$user->role_id !== $roleMap[$role]) {
            Auth::logout();
            return back()->withErrors([
                'user_id' => "You are not authorized to log in as " . ucfirst($role) . ".",
            ]);
        }

        return match ($role) {
               'admin' => redirect()->route('admin.documents.dashboard')->with('greeting', 'Welcome back, Admin!'),
            'teacher' => redirect()->route('teacher.dashboard')->with('greeting', 'Welcome back, Teacher!'),
            'student' => redirect()->route('student.dashboard')->with('greeting', 'Welcome back, Student!'),
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
