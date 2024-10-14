<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Mail\AnnouncementPosted;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class TeacherAnnouncementController extends Controller
{
    public function index()
    {
        $announcements = auth()->user()->teacher->announcements;
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayAnnouncements = $announcements->filter(function ($announcement) use ($today) {
            return $announcement->created_at->isSameDay($today);
        });

        $yesterdayAnnouncements = $announcements->filter(function ($announcement) use ($yesterday) {
            return $announcement->created_at->isSameDay($yesterday);
        });

        $otherAnnouncements = $announcements->filter(function ($announcement) use ($today, $yesterday) {
            return !$announcement->created_at->isSameDay($today) && !$announcement->created_at->isSameDay($yesterday);
        });

        return view('pages.teachers.announcements.index', compact('todayAnnouncements', 'yesterdayAnnouncements', 'otherAnnouncements'));
    }

    public function create()
    {
        return view('pages.teachers.announcements.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // dd(auth()->user()->teacher->classes->flatMap->students);
        $anc = Announcement::create([
            'title' => $request->title,
            'content' => $request->description,
            'teacher_id' => auth()->user()->teacher->id, // Directly access teacher id
            'class_id' => auth()->user()->teacher->classes()->first()->id, // Get the first class
            'for' => 'students',
            'created_at' => now(),
        ]);

        // Send mail to students
        defer(function () use ($anc) { // Pass $anc to the deferred function
            // Retrieve the class and eager load the students' user relationships
            $class = auth()->user()->teacher->classes()->first();
            $students = $class->students()->with('user')->get(); // Eagerly load the 'user' relationship

            // Send mail to each student in the class
            foreach ($students as $student) {
                Mail::to($student->user->email)
                    ->send(new AnnouncementPosted($anc));
            }
        });

        return redirect('/teacher/announcements/show')->with('success', 'Announcement created successfully');
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
