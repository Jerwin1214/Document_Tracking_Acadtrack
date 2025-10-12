<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Classes; // ⚡ ensure your Class model is imported
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherMainController extends Controller
{
    /**
     * ✅ Teacher dashboard
     * Loads the authenticated teacher along with their classes, subjects,
     * total assigned students, and total assigned subjects
     */
   public function index()
{
    $teacher = Teacher::with('classes')->where('user_id', auth()->id())->firstOrFail();

    // Classes assigned by admin
    $classIds = $teacher->classes->pluck('id')->toArray();

    // Fetch students from those classes
    if (!empty($classIds)) {
        $studentIds = DB::table('class_student')
            ->whereIn('class_id', $classIds)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $students = Student::whereIn('id', $studentIds)->get();
    } else {
        $students = collect(); // no students if no classes assigned
    }

    // Count values
    $totalStudents = $students->count();
    $totalSubjects = $teacher->subjects()->count();

    // ✅ also pass classes for blade
    $classes = $teacher->classes;

    return view('pages.teachers.dashboard', compact(
        'teacher',
        'students',
        'totalStudents',
        'totalSubjects',
        'classes'
    ));
}

    /**
     * ✅ Show teacher profile page
     * Ensures classes and subjects are loaded for display
     */
    public function showProfilePage()
    {
        $teacher = Teacher::with(['classes', 'subjects'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('pages.teachers.profile', compact('teacher'));
    }

    /**
     * ✅ Show teacher settings page
     */
    public function showSettingsPage()
    {
        $teacher = Teacher::where('user_id', auth()->id())->firstOrFail();
        return view('pages.teachers.settings', compact('teacher'));
    }

    /**
     * ✅ Update teacher email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'string', 'max:255', 'unique:users,email,' . auth()->id()],
        ]);

        $user = auth()->user();
        if ($request->email != $user->email) {
            $user->update(['email' => $request->email]);

            // Optional: logout after email change
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/')->with('success', 'Email updated. Please login again.');
        }

        return back()->with('info', 'Email is unchanged.');
    }

    /**
     * ✅ Update teacher password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();

        // Verify old password
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Old password is incorrect.');
        }

        // Update password
        $user->password = bcrypt($request->password);
        $user->save();

        // Regenerate session to keep teacher logged in
        $request->session()->regenerate();

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * ✅ Show all students for teacher's assigned classes
     */
   public function showStudents()
{
    $teacher = Teacher::with('classes')
        ->where('user_id', auth()->id())
        ->firstOrFail();

    $classIds = $teacher->classes->pluck('id')->toArray();

    if (empty($classIds)) {
        $students = Student::whereRaw('0=1')->paginate(10); // no students
    } else {
        $studentIds = DB::table('class_student')
            ->whereIn('class_id', $classIds)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $students = Student::whereIn('id', $studentIds)
            ->with([
                'classes' => function ($q) use ($classIds) {
                    $q->whereIn('classes.id', $classIds);
                },
                'guardian'
            ])
            ->paginate(10);
    }

    // ✅ Pass $classes too
    $classes = $teacher->classes;

    return view('pages.teachers.students.index', compact('students', 'classes'));
}


    /**
     * ✅ Show students of a specific class selected by the teacher
     */
    public function classStudents($classId)
    {
        $teacher = Teacher::with('classes')->where('user_id', auth()->id())->firstOrFail();
        $classes = $teacher->classes;

        // make sure class belongs to teacher
        $class = $classes->where('id', $classId)->firstOrFail();

        $studentIds = DB::table('class_student')
            ->where('class_id', $class->id)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $students = Student::whereIn('id', $studentIds)
            ->with(['classes', 'guardian'])
            ->paginate(10);

        return view('pages.teachers.students.index', [
            'classes' => $classes,
            'students' => $students,
            'currentClass' => $class
        ]);
    }
public function subjectStudents($subjectId)
{
    $teacher = Teacher::with('classes')->where('user_id', auth()->id())->firstOrFail();

    $subject = $teacher->subjects()->where('subjects.id', $subjectId)->firstOrFail();

    // Get classes assigned to this teacher
    $classIds = $teacher->classes->pluck('id')->toArray();

    if (empty($classIds)) {
        $students = collect(); // no students if no classes
    } else {
        // Get student IDs that are both in teacher's classes AND assigned to this subject
        $studentIds = DB::table('class_student')
            ->join('student_subject', 'class_student.student_id', '=', 'student_subject.student_id')
            ->whereIn('class_student.class_id', $classIds)
            ->where('student_subject.subject_id', $subject->id)
            ->pluck('student_id')
            ->unique()
            ->toArray();

        $students = Student::whereIn('id', $studentIds)->get();
    }

    return view('pages.teachers.grades.subject-students', compact('students', 'subject'));
}



}
