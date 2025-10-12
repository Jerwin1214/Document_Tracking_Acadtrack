<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'subjects'])
            ->latest()
            ->paginate(20);

        return view('pages.admin.teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('pages.admin.teacher.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'salutation' => ['required', 'string', 'max:5'],
            'first_name' => ['required', 'string', 'max:30'],
            'middle_name' => ['required', 'string', 'max:30'],
            'last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'in:Male,Female'],
            'dob' => ['required', 'date'],
            'address' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'string', 'max:255', 'unique:users,user_id'],
            'password' => ['required', 'string', 'min:5'],
        ]);

        $user = User::create([
            'user_id' => $request->user_id,
            'password' => Hash::make($request->password),
            'role_id' => 3, // Teacher role
            'created_at' => now(),
        ]);

        Teacher::create([
            'salutation' => $request->salutation,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'address' => $request->address,
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher added successfully');
    }

    public function showAllTeachers(Request $request)
    {
        $status = $request->query('status', 'active');

        $teachers = Teacher::with(['user', 'subjects'])
            ->where('status', $status)
            ->select(['id', 'salutation', 'first_name', 'last_name', 'user_id'])
            ->paginate(20);

        return view('pages.admin.teacher.index', compact('teachers'));
    }

    public function showArchived()
    {
        $archivedTeachers = Teacher::with(['user', 'subjects'])
            ->where('status', 'archived')
            ->paginate(20);

        return view('pages.admin.teacher.archived', compact('archivedTeachers'));
    }

    public function unarchive($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->status = 'active';
        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher unarchived successfully.');
    }

    public function show(Teacher $teacher)
    {
        return view('pages.admin.teacher.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $subjects = Cache::remember('subjects_list', 60, function () {
            return Subject::all();
        });

        return view('pages.admin.teacher.edit', compact('teacher', 'subjects'));
    }

 public function update(Request $request, Teacher $teacher)
{
    $request->validate([
        'salutation' => ['required', 'string', 'max:5'],
        'first_name' => ['required', 'string', 'max:30'],
        'middle_name' => ['required', 'string', 'max:30'],
        'last_name' => ['required', 'string', 'max:50'],
        'gender' => ['required', 'string', 'in:Male,Female'],
        'dob' => ['required', 'date'],
        'address' => ['required', 'string', 'max:255'],
        'user_id' => [
            'required',
            'string',
            'max:50',
            \Illuminate\Validation\Rule::unique('users', 'user_id')->ignore($teacher->user->id ?? null),
        ],
    ]);

    // Update teacher table
    $teacher->update([
        'salutation' => $request->salutation,
        'first_name' => $request->first_name,
        'middle_name' => $request->middle_name,
        'last_name' => $request->last_name,
        'gender' => $request->gender,
        'dob' => $request->dob,
        'address' => $request->address,
    ]);

    // Update the login user ID in the users table
    if ($teacher->user) {
        $teacher->user->update([
            'user_id' => $request->user_id,
        ]);
    }

    return redirect()->route('admin.teachers.edit', $teacher->id)
                     ->with('success', 'Teacher information and login ID updated successfully');
}


    public function archive($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->status = 'archived';
        $teacher->save();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher archived successfully.');
    }

    // Show assign class form
    public function assignClassForm($id)
    {
        $teacher = Teacher::with('classes')->findOrFail($id);
        $classes = Classes::all();

        return view('pages.admin.teacher.assign-classes', compact('teacher', 'classes'));
    }

    // Handle assigning classes to teacher
    public function assignClass(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'classes' => 'required|array',
            'classes.*' => 'exists:classes,id',
        ]);

        // Sync selected classes
        $teacher->classes()->sync($request->classes);

        return redirect()->route('admin.teachers.show', $teacher->id)
            ->with('success', 'Classes assigned successfully.');
    }

    // ------------------------
    // Show students for a subject
    // ------------------------
    public function subjectStudents($subjectId)
    {
        $subject = Subject::find($subjectId);

        if (!$subject) {
            return redirect()->back()->with('error', 'Subject not found.');
        }

        // Fetch students based on year_level, section, and strand
        $students = Student::where('year_level', $subject->year_level)
                           ->where('section', $subject->section)
                           ->when($subject->strand, function($query) use ($subject) {
                               $query->where('strand', $subject->strand);
                           })
                           ->where('status', 'active')
                           ->get();

        return view('pages.teachers.subject-students', compact('subject', 'students'));
    }

}
