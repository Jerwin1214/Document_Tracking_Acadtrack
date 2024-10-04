<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherMainController extends Controller
{
    public function showProfilePage() {
        return view('pages.teachers.profile');
    }

    public function showSettingsPage() {
        return view('pages.teachers.settings');
    }

    public function updateSettings() {
        // TODO: Implement updateSettings method
    }
}
