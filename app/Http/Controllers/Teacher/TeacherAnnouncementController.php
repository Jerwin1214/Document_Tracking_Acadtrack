<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class TeacherAnnouncementController extends Controller
{
    public function index()
    {
        return view('pages.teachers.announcements.index');
    }

    public function create()
    {
        return view('pages.teachers.announcements.add');
    }

    public function store(Request $request)
    {
        // TODO: Implement store() method
    }

    public function show(Announcement $announcement)
    {
        return view('pages.teachers.announcements.show', ['announcement' => $announcement]);
    }

    public function edit(Announcement $announcement)
    {
        return view('pages.teachers.announcements.edit', ['announcement' => $announcement]);
    }

    public function update(Request $request, Announcement $announcement)
    {
        // TODO: Implement update() method
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect('/teacher/announcements/show')->with('success', 'Announcement deleted successfully');
    }
}
