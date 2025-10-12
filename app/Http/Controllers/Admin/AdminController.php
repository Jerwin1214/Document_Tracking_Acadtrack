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
// ================= DASHBOARD
public function index()
{
    // Basic counts
    $counts = (object)[
        'students_count' => Student::count(),
        'teachers_count' => Teacher::count(),
        'subjects_count' => Subject::count(),
        'classes_count'  => SchoolClass::count(),
        'admins_count'   => Admin::count(),
    ];

    // Student counts by department
    $departmentCounts = [
        'Kindergarten' => Student::where('department', 'Kindergarten')->count(),
        'Elementary'   => Student::where('department', 'Elementary')->count(),
        'Junior High'  => Student::where('department', 'Junior High')->count(),
        'Senior High'  => Student::where('department', 'Senior High')->count(),
    ];

    // Student counts by grade level (Grades 1–12 + Kinder)
    $gradeCounts = [];
    for ($i = 1; $i <= 12; $i++) {
        $gradeCounts['Grade ' . $i] = Student::where('year_level', $i)->count();
    }
    $gradeCounts['Kindergarten'] = Student::where('department', 'Kindergarten')->count();

    // Senior High: count by grade + strand
    $seniorHighStrandCounts = [
        'Grade 11 ABM'   => Student::where('department', 'Senior High')->where('year_level', 11)->where('strand', 'ABM')->count(),
        'Grade 11 STEM'  => Student::where('department', 'Senior High')->where('year_level', 11)->where('strand', 'STEM')->count(),
        'Grade 11 HUMSS' => Student::where('department', 'Senior High')->where('year_level', 11)->where('strand', 'HUMSS')->count(),
        'Grade 11 GAS'   => Student::where('department', 'Senior High')->where('year_level', 11)->where('strand', 'GAS')->count(),

        'Grade 12 ABM'   => Student::where('department', 'Senior High')->where('year_level', 12)->where('strand', 'ABM')->count(),
        'Grade 12 STEM'  => Student::where('department', 'Senior High')->where('year_level', 12)->where('strand', 'STEM')->count(),
        'Grade 12 HUMSS' => Student::where('department', 'Senior High')->where('year_level', 12)->where('strand', 'HUMSS')->count(),
        'Grade 12 GAS'   => Student::where('department', 'Senior High')->where('year_level', 12)->where('strand', 'GAS')->count(),
    ];

    // Gender counts
    $genderCounts = Student::select('gender', DB::raw('COUNT(*) as count'))
        ->groupBy('gender')
        ->pluck('count', 'gender')
        ->toArray();

    $genderCounts = array_merge(['Male' => 0, 'Female' => 0], $genderCounts);

    return view('pages.admin.dashboard', compact(
        'counts',
        'departmentCounts',
        'gradeCounts',
        'seniorHighStrandCounts',
        'genderCounts'
    ));
}



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

    // ================= MESSAGES
    public function showMessages()
    {
        return view('pages.admin.messages.index');
    }

    public function showMessage()
    {
        return view('pages.admin.messages.show');
    }

}
