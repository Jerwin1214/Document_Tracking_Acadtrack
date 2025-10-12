<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;

class StudentStdController extends Controller
{
    /**
     * Student dashboard
     */
    public function dashboard(): View|Factory|Application
    {
        $student = Student::with(['class', 'subjects'])
            ->where('user_id', auth()->id())
            ->first();

        $totalSubjects = $student && $student->subjects ? $student->subjects->count() : 0;

        $announcements = \App\Models\Announcement::latest()->take(5)->get();

        $quotes = [
            "Education is the most powerful weapon you can use to change the world.",
            "Success doesn’t come to you, you go to it.",
            "Your limitation—it’s only your imagination.",
            "Great things never come from comfort zones.",
            "Dream it. Wish it. Do it.",
        ];
        $quote = $quotes[array_rand($quotes)];

        return view('pages.students.dashboard', compact(
            'student',
            'totalSubjects',
            'announcements',
            'quote'
        ));
    }

    /**
     * Show the student profile page
     */
    public function showProfilePage(): View|Factory|Application
    {
        $student = Student::with(['guardian', 'class'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$student) {
            abort(404, 'Student not found.');
        }

        return view('pages.students.profile', ['student' => $student]);
    }

    /**
     * Show the settings page (Change Password)
     */
    public function showSettingsPage(): View|Factory|Application
    {
        return view('pages.students.settings');
    }

    /**
     * Update student password
     */
    public function updateSettings(Request $request)
    {
        // Validate input
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $student = Auth::user();

        // Verify old password
        if (!Hash::check($request->old_password, $student->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        // Update with new password
        $student->password = Hash::make($request->password);
        $student->save();

        return redirect()->back()->with('success', 'Password changed successfully!');
    }
}
