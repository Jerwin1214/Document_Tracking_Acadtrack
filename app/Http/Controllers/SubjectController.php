<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    public function index()
    {
        // 
    }

    public function create()
    {
        return view('pages.admin.subject.add');
    }

    public function showAllSubjects()
    {
        $subjects = Subject::all();
        return view('pages.admin.subject.index', ['subjects' => $subjects]);
    }

    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

        // create a new subject
        Subject::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect('/admin/subjects/show')->with('success', 'Subject added successfully');
    }

    public function edit(Subject $subject)
    {
        return view('pages.admin.subject.edit', ['subject' => $subject]);
    }

    public function update(Request $request, Subject $subject)
    {
        // validate the request
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

        // update the subject
        $subject->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
        ]);

        return redirect('/admin/subjects/show')->with('success', 'Subject updated successfully');
    }

    public function destroy(Request $request, Subject $subject)
    {
        $subject->delete();
        return redirect('/admin/subjects/show')->with('success', 'Subject deleted successfully');
    }

    public function assignTeachersView()
    {
        return view('pages.admin.subject.assign-teachers', ['subjects' => Subject::all(), 'teachers' => Teacher::all()]);
    }

    public function assignTeachers(Request $request)
    {
        // validate the inputs
        $request->validate([
            'teacher' => ['required'],
            'subjects' => ['required'],
        ]);
        foreach ($request->subjects as $subject_id) {
            DB::table('subject_teacher')->insert([
                'subject_id' => $subject_id,
                'teacher_id' => $request->teacher,
                'created_at' => now(),
            ]);
        }
        return redirect('/admin/teachers/show')->with('success', 'Subject assiged to teacher successfully');
    }

    public function showAssignedSubjectsForTeacher(Teacher $teacher)
    {
        $subjects = $teacher->subjects;
        return response($subjects);
    }
}
