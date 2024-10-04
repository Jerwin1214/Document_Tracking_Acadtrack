<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherMainController extends Controller
{
    public function showProfilePage() {
        $teacher = Teacher::select(['first_name', 'last_name'])->where('user_id', auth()->user()->id)->first();
        return view('pages.teachers.profile', ['teacher' => $teacher]);
    }

    public function showSettingsPage() {
        return view('pages.teachers.settings');
    }

    public function updateSettings() {
        // TODO: Implement updateSettings method
    }
}
