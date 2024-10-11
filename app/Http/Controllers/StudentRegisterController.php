<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentRegisterController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Request $request)
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // validate user inputs
        // dd($request->all());
        $request->validate([
            'first_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
        ]);

        // create a new user
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'created_at' => now(),
        ]);

        Student::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        Auth::login($user);

        return redirect()->route('student.dashboard');
    }
}
