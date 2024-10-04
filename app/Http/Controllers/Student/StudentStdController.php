<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class StudentStdController extends Controller
{
    public function showProfilePage()
    {
//        dd(Student::where('user_id', auth()->user()->id)->get());
        $student_data = Student::select(['first_name', 'last_name'])->where('user_id', auth()->user()->id)->first();
//        dd($student_data);
        return view('pages.students.profile', ['student' => $student_data]);
    }

    public function showSettingsPage(): View|Factory|Application
    {
        return view('pages.students.settings');
    }

    public function updateSettings()
    {
        // TODO: Implement updateSettings method
    }
}
