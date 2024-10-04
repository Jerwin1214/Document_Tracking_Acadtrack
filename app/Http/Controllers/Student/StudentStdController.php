<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentStdController extends Controller
{
    public function showProfilePage() {
        return view('pages.students.profile');
    }

    public function showSettingsPage() {
        return view('pages.students.settings');
    }

    public function updateSettings() {
        // TODO: Implement updateSettings method
    }
}
