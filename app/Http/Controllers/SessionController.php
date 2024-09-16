<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $attr = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // attempt to login the user
        if (!Auth::attempt($attr)) {
            // if the login fails
            return back()->withErrors(['email' => 'Your provided credentials could not be verified.']);
        }

        // regenerate the session token
        request()->session()->regenerate();
        // redirect to the admin page
        // TODO: create the Admin Dashboard
    }

    public function destroy()
    {
        // logout functionality
        Auth::logout();
        // redirect to the login page
        return redirect('/');
    }
}
