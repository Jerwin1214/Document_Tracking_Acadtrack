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
        // login functionality
        // validate the request
        $attrs = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // dd($attr);

        // redirect to the dashboard according to the user role
        if (!Auth::attempt($attrs)) {
            throw ValidationException::withMessages([
                'email' => 'Your provided credentials could not be verified.'
            ]);
        }

        // regenerate the session token
        request()->session()->regenerate();

        // check the role of the current user
        if (auth()->user()->role->name == 'Admin') {
            return redirect('/admin/dashboard');
        } elseif (auth()->user()->role->name == 'Student') {
            return redirect('/student/dashboard');
        } elseif (auth()->user()->role->name == 'Teacher') {
            return redirect('/teacher/dashboard');
        }
    }

    public function destroy()
    {
        // logout functionality
        Auth::logout();
        // redirect to the login page
        return redirect('/');
    }
}
