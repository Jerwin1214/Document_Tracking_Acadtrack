<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Subject;
use App\Models\SubjectStream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    public function index()
    {
        $classes = DB::table('classes')
            ->leftJoin('teachers', 'classes.teacher_id', '=', 'teachers.id')
            ->select(
                'classes.id',
                'classes.department',
                'classes.year_level',
                'classes.section',
                'classes.teacher_id',
                'classes.year',
                'classes.subject_stream_id',
                'teachers.first_name as teacher_first_name',
                'teachers.last_name as teacher_last_name',
                DB::raw('(SELECT COUNT(*) FROM students
                          WHERE students.department = classes.department
                          AND students.year_level = classes.year_level
                          AND (classes.subject_stream_id IS NULL OR students.strand = (SELECT stream_name FROM subject_streams WHERE id = classes.subject_stream_id))
                          AND students.section = classes.section) as students_count')
            )
            ->paginate(20);

        return view('pages.admin.class.index', ['classes' => $classes]);
    }

   public function create()
{
    // Fetch all teachers (no cache for now)
    $teachers = Teacher::select(['id', 'salutation', 'first_name', 'last_name'])->get();

    // Fetch all streams for Senior High
    $streams = SubjectStream::all();

    return view('pages.admin.class.add', compact('teachers', 'streams'));
}

   public function store(Request $request)
{
    $request->validate([
        'department' => 'required|string',
        'year_level' => 'required|string',
        'section' => 'required|string',
        'teacher_id' => 'required|exists:teachers,id',
        'year' => 'required|string',
        'subject_stream_id' => 'nullable|exists:subject_streams,id',
    ]);

    // Build the class name dynamically
    $className = $request->year_level . ' - ' . $request->section;

    $class = new Classes();
    $class->department = $request->department;
    $class->year_level = $request->year_level;
    $class->section = $request->section;
    $class->name = $className;
    $class->teacher_id = $request->teacher_id;
    $class->year = $request->year;

    // Only assign subject_stream_id if it's Senior High
    if ($request->department === 'Senior High') {
        $class->subject_stream_id = $request->subject_stream_id;
    } else {
        $class->subject_stream_id = null;
    }

    $class->save();

    return redirect()->route('admin.classes.index')
                     ->with('success', 'Class added successfully!');
}

    public function show(Classes $class)
    {
        $class = Classes::with('teacher')->find($class->id);

        $subjects = DB::table('subject_teacher')
            ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
            ->where('subject_teacher.teacher_id', $class->teacher_id)
            ->select('subjects.*')
            ->get();

        $studentsQuery = \App\Models\Student::with('guardian')
            ->where('department', $class->department)
            ->where('year_level', $class->year_level);

        if ($class->subject_stream_id) {
            $studentsQuery->where('strand', $class->subject_stream_id);
        }

        $students = $studentsQuery->get();

        return view('pages.admin.class.show', compact('class', 'subjects', 'students'));
    }

    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        $grades = Grade::all();
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $streams = SubjectStream::all();

        return view('pages.admin.class.edit', compact('class', 'grades', 'subjects', 'teachers', 'streams'));
    }

    public function update(Request $request, Classes $class)
    {
        $request->validate([
            'department' => 'required|string',
            'year_level' => 'required|string',
            'section' => 'required|string',
            'teacher_id' => 'required|integer',
            'year' => 'required|string',
        ]);

        $class->update([
            'department' => $request->department,
            'year_level' => $request->year_level,
            'section' => $request->section,
            'teacher_id' => $request->teacher_id,
            'year' => $request->year,
            'subject_stream_id' => $request->subject_stream_id ?? null,
        ]);

        return redirect()->route('admin.classes.index')->with('success', 'Class updated successfully!');
    }

    public function destroy(Classes $class)
    {
        $class->delete();
        return redirect()->route('admin.classes.index')->with('success', 'Class deleted successfully!');
    }

    public function assignStudentsView(Classes $class)
    {
        $unassignedStudents = DB::table('students')
            ->leftJoin('class_student', 'students.id', '=', 'class_student.student_id')
            ->whereNull('class_student.class_id')
            ->select('students.*')
            ->get();

        return view('pages.admin.class.assign-students', [
            'class' => $class,
            'students' => $unassignedStudents,
        ]);
    }

    public function assignStudents(Request $request, Classes $class)
    {
        foreach ($request->students as $studentId) {
            DB::table('class_student')->insert([
                'class_id' => $class->id,
                'student_id' => $studentId,
                'created_at' => now(),
            ]);

            DB::table('students')->where('id', $studentId)->update([
                'department' => $class->department,
                'year_level' => $class->year_level,
                'section' => $class->section,
                'strand' => $class->subject_stream_id,
            ]);
        }

        return redirect()->route('admin.classes.index')->with('success', 'Students assigned successfully!');
    }
}
