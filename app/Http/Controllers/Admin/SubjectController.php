<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $subjects = Subject::with('teachers')->paginate(200);
        return view('pages.admin.subject.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

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
        $request->validate([
            'name' => ['required', 'string', 'min:3'],
            'code' => ['required', 'string', 'min:2'],
            'description' => ['nullable', 'string'],
        ]);

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
        $teachers = Teacher::all();
        $subjectsByLevel = [];

        // Kindergarten
        $subjectsByLevel['kindergarten'] = Subject::where('level', 'kindergarten')
            ->get(['id','name'])->toArray();

        // Elementary
        foreach (['1','2','3','4','5','6'] as $grade) {
            $subjectsByLevel['elementary'][$grade] = Subject::where('level', 'elementary')
                ->where('grade', $grade)
                ->get(['id','name'])->toArray();
        }

        // Junior High
        foreach (['7','8','9','10'] as $grade) {
            $subjectsByLevel['junior_high'][$grade] = Subject::where('level', 'junior_high')
                ->where('grade', $grade)
                ->get(['id','name'])->toArray();
        }

        // ✅ Senior High (separate grade + strand)
        foreach (['11','12'] as $grade) {
            foreach (['STEM','ABM','HUMSS','GAS'] as $strand) {
                $key = $grade . '-' . $strand;
                $subjectsByLevel['senior_high'][$key] = Subject::where('level', 'senior_high')
                    ->where('grade', $grade)
                    ->where('strand', $strand)
                    ->get(['id','name'])
                    ->toArray();
            }
        }

        return view('pages.admin.subject.assign-teachers', compact('teachers','subjectsByLevel'));
    }

    public function assignTeachers(Request $request)
    {
        $request->validate([
            'teacher' => ['required'],
            'subjects' => ['required'],
        ]);

        $existingSubjects = DB::table('subject_teacher')
            ->where('teacher_id', $request->teacher)
            ->pluck('subject_id')
            ->toArray();

        $newSubjects = array_diff($request->subjects, $existingSubjects);
        $removedSubjects = array_diff($existingSubjects, $request->subjects);

        if (!empty($newSubjects)) {
            foreach ($newSubjects as $subject_id) {
                DB::table('subject_teacher')->updateOrInsert([
                    'teacher_id' => $request->teacher,
                    'subject_id' => $subject_id
                ], [
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        if (!empty($removedSubjects)) {
            DB::table('subject_teacher')
                ->where('teacher_id', $request->teacher)
                ->whereIn('subject_id', $removedSubjects)
                ->delete();
        }

        return redirect('/admin/teachers/show')->with('success', 'Subject assigned to teacher successfully');
    }

    public function showAssignedSubjectsForTeacher(Teacher $teacher)
    {
        $subjects = $teacher->subjects;
        return response($subjects);
    }

    public function uploadSubjects(Request $request)
    {
        $request->validate([
            'file' => ['file', 'mimes:xls,xlsx'],
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            if ($rowIndex == 1) continue;

            $name = $sheet->getCell("A$rowIndex")->getValue();
            $code = $sheet->getCell("B$rowIndex")->getValue();
            $description = $sheet->getCell("C$rowIndex")->getValue();

            if ($name) {
                Subject::create([
                    'name' => $name,
                    'code' => $code,
                    'description' => $description,
                ]);
            }
        }

        return redirect('/admin/subjects/show')->with('success', 'Subjects uploaded successfully');
    }

    /** ------------------- NEW METHODS FOR LEVEL/GRADE SUBJECT ASSIGNMENT ------------------- */

    // Fetch subjects dynamically based on level and grade/strand
   public function getSubjects(Request $request)
{
    $teacherId = $request->teacher;
    $level = $request->level;
    $grade = $request->grade;
    $strand = $request->strand;

    $query = Subject::where('level', $level);

    if ($grade) {
        $query->where('grade', $grade);
    }

    // Show both core (no strand) and specialization (with strand)
    if ($strand) {
        $query->where(function($q) use ($strand) {
            $q->whereNull('strand')
              ->orWhere('strand', $strand);
        });
    }

    $subjects = $query->get();

    // Optional: check which subjects already assigned to this teacher
    $subjects->map(function ($subj) use ($teacherId) {
        $subj->assigned = $subj->teachers()
                               ->where('teacher_id', $teacherId)
                               ->exists();
        return $subj;
    });

    return response()->json($subjects);
}


    // Assign subjects based on level/grade to teacher
    public function assignSubjects(Request $request)
    {
        $request->validate([
            'teacher' => 'required|exists:teachers,id',
            'level' => 'required',
            'grade' => 'nullable',
            'subjects' => 'required|array'
        ]);

        $teacherId = $request->teacher;
        $level = $request->level;
        $grade = $request->grade;
        $subjects = $request->subjects;

        DB::table('subject_teacher_assignments')
            ->where('teacher_id', $teacherId)
            ->where('level', $level)
            ->when($grade, fn($q) => $q->where('grade', $grade))
            ->delete();

        foreach ($subjects as $subject) {
            DB::table('subject_teacher_assignments')->insert([
                'teacher_id' => $teacherId,
                'level' => $level,
                'grade' => $grade,
                'subject' => $subject,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->back()->with('success', 'Subjects assigned successfully!');
    }
}
