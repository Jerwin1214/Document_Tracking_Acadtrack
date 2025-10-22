<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Classes as SchoolClass;

class AdminController extends Controller
{

    // ================= PROFILE & SETTINGS
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
        $emailChanged = false;

        if ($request->email != $user->email) {
            $user->email = $request->email;
            $emailChanged = true;
        }

        if ($request->old_password && $request->password) {
            if (!Hash::check($request->old_password, $user->password)) {
                return back()->with('error', 'Old password is incorrect');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($emailChanged) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/')->with('success', 'Email and password changed. Please login again.');
        }

        return back()->with('success', 'Settings updated successfully.');
    }
// ================= Update profile
public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'password' => 'nullable|string|min:6|confirmed',
    ]);

    $user = auth()->user();
    $user->name = $request->name;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully!');
}
// Show Change Password Form
public function showPasswordForm()
{
    return view('pages.admin.change-password');
}

// Update Password
public function updatePassword(Request $request)
{
    $request->validate([
        'old_password' => 'required',
        'password' => 'required|string|min:8|confirmed',
    ]);

    $admin = auth()->user();

    // Check if current password matches
    if (!Hash::check($request->old_password, $admin->password)) {
        return back()->withErrors(['old_password' => 'Current password is incorrect']);
    }

    // Update password
    $admin->password = Hash::make($request->password);
    $admin->save();

    return back()->with('success', 'Password updated successfully');
}

public function updateEmail(Request $request)
{
    $request->validate([
        'email' => 'required|email|unique:users,email,' . auth()->id(),
    ]);

    $user = auth()->user();
    $user->email = $request->email;
    $user->save();

    return back()->with('success', '✅ Email updated successfully!');
}



}
