<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guardian;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StudentController extends Controller
{
    public function index()
    {
        return view('pages.students.dashboard');
    }

    public function create()
    {
        return view('pages.admin.student.add');
    }

    public function store(Request $request)
    {
        // Validate user inputs
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string', 'max:5'],
            'std_nic' => ['nullable', 'string', 'max:12'], // Nullable to allow empty input
            'dob' => ['required', 'date'],
            'index' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'], // Ensure email is unique
            'password' => ['required', 'string', 'min:5'],

            'initials' => ['nullable', 'string', 'max:10'],
            'g_first_name' => ['nullable', 'string', 'max:30'],
            'g_last_name' => ['nullable', 'string', 'max:50'],
            'g_nic' => ['nullable', 'string', 'max:12'],
            'g_phone' => ['nullable', 'string', 'max:10'],
        ]);

        // Use a transaction to ensure all data is stored correctly
        DB::transaction(function () use ($request) {
            // Create user
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_id' => 3, // Assuming role_id 3 is for students
                'created_at' => now(),
            ]);

            // Create guardian
            $guardian = Guardian::create([
                'initials' => $request->initials,
                'first_name' => $request->g_first_name,
                'last_name' => $request->g_last_name,
                'nic' => $request->g_nic,
                'phone_number' => $request->g_phone,
                'created_at' => now(),
            ]);

            // Create student
            Student::create([
                'first_name' => $request->std_first_name,
                'last_name' => $request->std_last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'nic' => $request->std_nic ?? "",
                'created_at' => now(),
                'user_id' => $user->id,
                'guardian_id' => $guardian->id,
            ]);
        });

        return redirect('/admin/students/show')->with('success', 'Student added successfully');
    }


    public function showAllStudents()
    {
        return view('pages.admin.student.index', [
            'students' => Student::select(['id', 'first_name', 'last_name'])->paginate(20)
        ]);
    }

    public function show(Student $student)
    {
        // Eager load the guardian to avoid extra queries
        $student->load('guardian');

        // Get the subjects that are specifically assigned to the student
        $subjects = DB::table('subjects')
            ->join('student_subjects', 'subjects.id', '=', 'student_subjects.subject_id')
            ->where('student_subjects.student_id', $student->id)
            ->select('subjects.code', 'subjects.name')
            ->get();

        return view('pages.admin.student.show', ['student' => $student, 'subjects' => $subjects]);
    }

    public function edit(Student $student)
    {
        return view('pages.admin.student.edit', ['student' => $student]);
    }

    public function update(Student $student, Request $request)
    {
        $request->validate([
            'std_first_name' => ['required', 'string', 'max:30'],
            'std_last_name' => ['required', 'string', 'max:50'],
            'gender' => ['required', 'string'],
            'std_nic' => ['string', 'max:12'],
            'dob' => ['required', 'date'],
            'initials' => ['required', 'string', 'max:10'],
            'g_first_name' => ['required', 'string', 'max:30'],
            'g_last_name' => ['required', 'string', 'max:50'],
            'g_nic' => ['required', 'string', 'max:12'],
            'g_phone' => ['required', 'string', 'max:10'],
        ]);

        // update the guardian information
        $student->guardian->update([
            'initials' => $request->initials,
            'first_name' => $request->g_first_name,
            'last_name' => $request->g_last_name,
            'nic' => $request->g_nic,
            'phone_number' => $request->g_phone,
            'updated_at' => now(),
        ]);

        // update the student information
        $student->update([
            'first_name' => $request->std_first_name,
            'last_name' => $request->std_last_name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nic' => $request->std_nic ?? "",
            'updated_at' => now(),
        ]);

        return redirect('/admin/students/show')->with('success', 'Student updated successfully');
    }

    public function destroy(Student $student)
    {
        $student->user()->delete();
        return redirect('/admin/students/show')->with('success', 'Student deleted successfully');
    }

    public function uploadStudents(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();

        // Define possible headers for each field
        $fieldMappings = [
            'first_name' => ['first name', 'firstname', 'f name'],
            'last_name' => ['last name', 'lastname', 'surname'],
            'gender' => ['gender', 'sex'],
            'nic' => ['nic', 'national id', 'id'],
            'dob' => ['dob', 'date of birth', 'birthdate'],
            'index_no' => ['index', 'index no.', 'index number'],
        ];

        // Get the header row and map columns
        $headerRow = [];
        foreach ($sheet->getRowIterator(1, 1)->current()->getCellIterator() as $cell) {
            $headerRow[] = strtolower(trim($cell->getValue()));
        }

        // Map headers to fields
        $columnMap = [];
        foreach ($fieldMappings as $dbField => $possibleHeaders) {
            foreach ($possibleHeaders as $header) {
                $columnIndex = array_search($header, $headerRow);
                if ($columnIndex !== false) {
                    $columnMap[$dbField] = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1);
                    break;
                }
            }
        }

        // Ensure required fields are mapped
        $requiredFields = ['first_name', 'last_name', 'gender', 'nic', 'dob', 'index_no'];
        foreach ($requiredFields as $field) {
            if (!isset($columnMap[$field])) {
                return redirect()->back()->withErrors("Missing required field: $field in the spreadsheet.");
            }
        }

        // Process each row and insert into the database
        foreach ($sheet->getRowIterator(2) as $row) {
            $data = [];
            $rowIndex = $row->getRowIndex();
            foreach ($columnMap as $dbField => $columnLetter) {
                $cellValue = $sheet->getCell($columnLetter . $rowIndex)->getValue();

                // Convert Excel date serial number to proper date format for 'dob'
                if ($dbField === 'dob' && is_numeric($cellValue)) {
                    $data[$dbField] = Carbon::instance(Date::excelToDateTimeObject($cellValue))->format('Y-m-d');
                } else {
                    $data[$dbField] = $cellValue;
                }
            }

            // Hardcode and 'user_id'
            $data['user_id'] = 1; // Hardcoded user_id value

            // Save the student record
            Student::create($data);
        }
        return redirect('/admin/students/show')->with('success', 'Students uploaded successfully');
    }
}
