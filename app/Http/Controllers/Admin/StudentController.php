<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Student, User, Classes, Teacher};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\Enrollment;

class StudentController extends Controller
{
   public function index()
{
    // Fetch enrollments with student info (optional: eager load the student relation)
    $enrollments = Enrollment::with('student') // assumes Enrollment model has student() relation
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

    return view('pages.admin.student.index', compact('enrollments'));
}

    public function create()
    {
        $lastStudent = Student::orderBy('id', 'desc')->first();
        $year = date('Y');
        $number = $lastStudent ? intval(substr($lastStudent->student_id, 5)) + 1 : 1;
        $nextStudentId = sprintf('%s-%04d', $year, $number);

        return view('pages.admin.student.add', compact('nextStudentId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'std_first_name' => 'required|string|max:255',
            'std_middle_name' => 'nullable|string|max:255',
            'std_last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'lrn' => ['required','digits:12','regex:/^[0-9]{12}$/','unique:students,lrn'],
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6',

            'guardian_first_name' => 'required|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:5',
            'guardian_last_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
        ]);

        DB::beginTransaction();

        try {
            $year = now()->year;
            $lastStudent = Student::whereYear('created_at', $year)->latest('id')->first();
            $newNumber = $lastStudent && $lastStudent->student_id
                ? str_pad(((int)substr($lastStudent->student_id, 5)) + 1, 4, '0', STR_PAD_LEFT)
                : '0001';

            $studentId = $year . '-' . $newNumber;
            $age = Carbon::parse($validated['dob'])->age;

            // 1️⃣ Create user account
            $user = User::create([
                'user_id' => $studentId,
                'role_id' => 2,
                'password' => bcrypt($validated['password']),
                'email_verified_at' => now(),
                'is_active' => 1,
            ]);

            // 2️⃣ Create student
            $student = Student::create([
                'user_id' => $user->id,
                'student_id' => $studentId,
                'first_name' => $validated['std_first_name'],
                'middle_name' => $validated['std_middle_name'],
                'last_name' => $validated['std_last_name'],
                'gender' => $validated['gender'],
                'lrn' => $validated['lrn'],
                'dob' => $validated['dob'],
                'age' => $age,
                'address' => $validated['address'],
                'status' => 'active',
                'department' => $request->department ?? null,
                'year_level' => $request->year_level ?? null,
                'section' => $request->section ?? null,
                'strand' => $request->strand ?? null,
                'guardian_first_name' => $validated['guardian_first_name'],
                'guardian_middle_name' => $validated['guardian_middle_name'],
                'guardian_last_name' => $validated['guardian_last_name'],
                'guardian_contact' => $validated['guardian_contact'],
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Student added successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to add student: ' . $e->getMessage());
        }
    }

    public function edit(Student $student)
    {
        return view('pages.admin.student.edit', compact('student'));
    }

    public function update(Student $student, Request $request)
    {
        $validated = $request->validate([
            'lrn' => ['required','digits:12', Rule::unique('students', 'lrn')->ignore($student->id)],
            'std_first_name' => 'required|string|max:255',
            'std_middle_name' => 'nullable|string|max:255',
            'std_last_name' => 'required|string|max:255',
            'gender' => 'required|in:Male,Female',
            'dob' => 'required|date',
            'address' => 'required|string|max:255',

            'guardian_first_name' => 'required|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:5',
            'guardian_last_name' => 'required|string|max:255',
            'guardian_contact' => 'required|string|max:20',
        ]);

        $student->update([
            'lrn' => $validated['lrn'],
            'first_name' => $validated['std_first_name'],
            'middle_name' => $validated['std_middle_name'],
            'last_name' => $validated['std_last_name'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'address' => $validated['address'],
            'age' => Carbon::parse($validated['dob'])->age,
            'guardian_first_name' => $validated['guardian_first_name'],
            'guardian_middle_name' => $validated['guardian_middle_name'],
            'guardian_last_name' => $validated['guardian_last_name'],
            'guardian_contact' => $validated['guardian_contact'],
        ]);

        return redirect()->route('admin.students.edit', $student->id)
                         ->with('success', 'Student updated successfully!');
    }

    public function archive($id)
    {
        $student = Student::findOrFail($id);
        $student->status = 'archived';
        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'Student archived successfully.');
    }

    public function unarchive($id)
    {
        $student = Student::findOrFail($id);
        $student->status = 'active';
        $student->save();

        return redirect()->route('admin.students.index')->with('success', 'Student unarchived successfully.');
    }

    public function assignDepartment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'department' => 'required|string',
            'year_level' => 'nullable|string',
            'section' => 'nullable|string',
            'strand' => 'nullable|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        $student->department = $request->department;
        $student->year_level = $request->year_level ?? $student->year_level;
        $student->section = $request->section ?? $student->section;
        $student->strand = $request->strand ?? $student->strand;
        $student->save();

        // Automatic class link
        $currentYear = date('Y') . '-' . (date('Y') + 1);
        $class = Classes::where('department', $student->department)
                        ->where('year_level', $student->year_level)
                        ->where('section', $student->section)
                        ->where('year', $currentYear)
                        ->first();

        if ($class) {
            $student->classes()->sync([$class->id]);
        } else {
            Log::warning("No matching class found for Student ID {$student->id}");
        }

        return redirect()->back()->with('success', 'Student assigned successfully.');
    }

    public function uploadStudents(Request $request)
    {
        try {
            $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
            $file = $request->file('file');
            $data = \Maatwebsite\Excel\Facades\Excel::toArray([], $file)[0];

            foreach ($data as $index => $row) {
                if ($index === 0) continue;

                $lrn = trim($row[10]);
                if (empty($lrn)) continue;
                if (Student::where('lrn', $lrn)->exists()) continue;

                $user = User::create([
                    'user_id' => $lrn,
                    'password' => bcrypt('password'),
                    'role_id' => 2,
                    'email_verified_at' => now(),
                    'is_active' => 1,
                ]);

                Student::create([
                    'first_name' => $row[6],
                    'middle_name' => $row[7],
                    'last_name' => $row[5],
                    'gender' => $row[15],
                    'dob' => Date::excelToDateTimeObject($row[13])->format('Y-m-d'),
                    'lrn' => $lrn,
                    'address' => $row[24], // current_house_no or similar
                    'user_id' => $user->id,
                    'status' => 'active',
                    'age' => Carbon::parse(Date::excelToDateTimeObject($row[13])->format('Y-m-d'))->age,
                    'guardian_first_name' => $row[48],
                    'guardian_middle_name' => $row[49],
                    'guardian_last_name' => $row[47],
                    'guardian_contact' => $row[50],
                ]);
            }

            return back()->with('success', 'Students uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Bulk upload error: ' . $e->getMessage());
            return back()->withErrors(['file' => 'Bulk upload failed. Check the file format and content.']);
        }
    }

    public function myStudents()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->back()->withErrors(['error' => 'You are not assigned as a teacher.']);
        }

        $students = Student::whereHas('classes', function($query) use ($teacher) {
            $query->whereIn('classes.id', $teacher->classes->pluck('id'));
        })
        ->with('classes')
        ->paginate(10);

        return view('pages.teachers.students.index', compact('students'));
    }
}
