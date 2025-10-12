<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class TeacherStudentController extends Controller
{
    /**
     * Display the list of students assigned to this teacher
     */
 public function index()
{
    $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
    $currentYear = date('Y') . '-' . (date('Y') + 1);

    // Get all classes assigned to this teacher for the current year
    $classes = $teacher->classes()->where('year', $currentYear)->get();
    $currentClass = $classes->first();

    // Get students assigned to any of this teacher's classes for the current year
    $students = Student::whereHas('classes', function ($q) use ($classes, $currentYear) {
            $q->whereIn('classes.id', $classes->pluck('id')) // check if student is in any of teacher's classes
              ->where('year', $currentYear);
        })
        ->with(['guardian', 'classes' => function($q) use ($classes, $currentYear) {
            $q->whereIn('classes.id', $classes->pluck('id'))
              ->where('year', $currentYear);
        }])
        ->paginate(20);

    return view('pages.teachers.students.index', compact('students', 'currentClass', 'classes'));
}


    /**
     * Show a single student's details (read-only)
     */
    public function show($studentId)
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();

        $currentYear = date('Y') . '-' . (date('Y') + 1);

        // Only allow teacher to view students assigned to their classes
        $student = Student::whereHas('classes', function ($q) use ($teacher, $currentYear) {
                $q->where('teacher_id', $teacher->id)
                  ->where('year', $currentYear);
            })
            ->with([
                'guardian',
                'classes' => function($q) use ($teacher, $currentYear) {
                    $q->where('teacher_id', $teacher->id)
                      ->where('year', $currentYear);
                }
            ])
            ->findOrFail($studentId);

        // Get subjects for this student (optional)
        $subjects = DB::table('subjects')
            ->join('student_subjects', 'subjects.id', '=', 'student_subjects.subject_id')
            ->where('student_subjects.student_id', $student->id)
            ->select('subjects.code', 'subjects.name')
            ->get();

        return view('pages.teachers.students.show', compact('student', 'subjects'));
    }


public function getStudentsAjax(Classes $class)
{
    $students = $class->students() // or your relationship
        ->select('students.id', 'lrn', 'first_name', 'middle_name', 'last_name', 'gender', 'dob')
        ->get()
        ->map(function($student) {
            return [
                'id' => $student->id,
                'lrn' => $student->lrn,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'gender' => $student->gender,
                'age' => $student->dob ? Carbon::parse($student->dob)->age : null,
            ];
        });

    return response()->json($students);
}
}
