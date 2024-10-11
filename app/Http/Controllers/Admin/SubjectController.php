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
        return view('pages.admin.subject.index', ['subjects' => Subject::select('id', 'name', 'code', 'description')->paginate(10)]);
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

        // Get the subjects already assigned to this teacher
        $existingSubjects = DB::table('subject_teacher')
            ->where('teacher_id', $request->teacher)
            ->pluck('subject_id')
            ->toArray();

        // Find subjects that need to be added (checked but not in the database)
        $newSubjects = array_diff($request->subjects, $existingSubjects);

        // Find subjects that need to be removed (unchecked but in the database)
        $removedSubjects = array_diff($existingSubjects, $request->subjects);

        // Insert new subjects
        if (!empty($newSubjects)) {
            foreach ($newSubjects as $subject_id) {
                DB::table('subject_teacher')->insert([
                    'subject_id' => $subject_id,
                    'teacher_id' => $request->teacher,
                    'created_at' => now(),
                ]);
            }
        }

        // Delete unassigned subjects
        if (!empty($removedSubjects)) {
            DB::table('subject_teacher')
                ->where('teacher_id', $request->teacher)
                ->whereIn('subject_id', $removedSubjects)
                ->delete();
        }
        return redirect('/admin/teachers/show')->with('success', 'Subject assiged to teacher successfully');
    }

    public function showAssignedSubjectsForTeacher(Teacher $teacher)
    {
        $subjects = $teacher->subjects;
        return response($subjects);
    }

    public function uploadSubjects(Request $request)
    {
        // validate the request
        $request->validate([
            'file' => ['file', 'mimes:xls,xlsx'],
        ]);

        // Load the uploaded file using PhpSpreadsheet
        $file = $request->file('file');
        //        dd($file);
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        // Loop through each row in the spreadsheet
        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
            // Skip header row if it exists (e.g., first row with column names)
            if ($rowIndex == 1) continue;

            $name = $sheet->getCell("A$rowIndex")->getValue();
            $code = $sheet->getCell("B$rowIndex")->getValue();
            $description = $sheet->getCell("C$rowIndex")->getValue();

            // Only save if the 'name' field is provided
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
}
