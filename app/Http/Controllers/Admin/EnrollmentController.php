<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Document;
use App\Models\StudentDocument;
use App\Helpers\ActivityLogger;
use Illuminate\Support\Facades\Storage;


class EnrollmentController extends Controller
{
    /**
     * Display all enrollments (active or archived)
     */
public function index(Request $request)
{
    $status = $request->get('status', 'active');
    $selectedGrade = $request->get('grade', 'all'); // Add this

    $enrollmentsQuery = Enrollment::where('status', $status)
        ->with(['studentDocuments.document'])
        ->orderBy('last_name');

    if ($selectedGrade !== 'all') {
        $enrollmentsQuery->where('grade_level', $selectedGrade);
    }

    $enrollments = $enrollmentsQuery->get();
    $allDocuments = Document::all();

    // Student population by grade (always full list)
    $grades = ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
    $studentsByGrade = [];
    foreach ($grades as $grade) {
        $studentsByGrade[$grade] = Enrollment::where('grade_level', $grade)
            ->where('status', $status)
            ->count();
    }

    return view('pages.admin.enrollment.index', compact(
        'enrollments',
        'status',
        'allDocuments',
        'studentsByGrade',
        'grades',
        'selectedGrade'
    ));
}

    /**
     * Show form to create a new enrollment
     */
    public function addEnrollment()
    {
        return view('pages.admin.enrollment.add-enrollment');
    }

    /**
     * Store a new enrollment record
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_year' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'psa_birth_cert_no' => 'nullable|string|max:255',
            'lrn' => 'nullable|string|max:12|unique:enrollments,lrn',
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'sex' => 'nullable|string|max:10',
            'mother_tongue' => 'nullable|string|max:255',
            'current_house_no' => 'nullable|string|max:255',
            'current_street' => 'nullable|string|max:255',
            'current_barangay' => 'nullable|string|max:255',
            'current_city' => 'nullable|string|max:255',
            'current_province' => 'nullable|string|max:255',
            'current_country' => 'nullable|string|max:255',
            'current_zip' => 'nullable|string|max:10',
            'permanent_house_no' => 'nullable|string|max:255',
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_city' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zip' => 'nullable|string|max:10',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_last_name' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:20',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:20',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',

            'indigenous_people' => 'nullable|string|max:255',
            'fourps_beneficiary' => 'nullable|string|max:255',
            'learner_with_disability' => 'nullable|string|max:255',
        ]);

        $validated['status'] = 'active';

        Enrollment::create($validated);
        ActivityLogger::log('Create', 'Enrollment', 'Added new enrollment for ' . $validated['first_name'] . ' ' . $validated['last_name']);

        return redirect()->route('admin.enrollment.index', ['status' => 'active'])
                         ->with('success', 'Student record saved successfully!');
    }

    /**
     * Show edit form for enrollment
     */
    public function edit($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return view('pages.admin.enrollment.edit-enrollment', compact('enrollment'));
    }

    /**
     * Update enrollment record
     */
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::findOrFail($id);

        $validated = $request->validate([
            'school_year' => 'required|string|max:255',
            'grade_level' => 'required|string|max:255', // ✅ Added
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'extension_name' => 'nullable|string|max:50',
            'psa_birth_cert_no' => 'nullable|string|max:255',
            'lrn' => 'nullable|string|max:12|unique:enrollments,lrn,' . $enrollment->id,
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'sex' => 'nullable|string|max:10',
            'mother_tongue' => 'nullable|string|max:255',
            'current_house_no' => 'nullable|string|max:255',
            'current_street' => 'nullable|string|max:255',
            'current_barangay' => 'nullable|string|max:255',
            'current_city' => 'nullable|string|max:255',
            'current_province' => 'nullable|string|max:255',
            'current_country' => 'nullable|string|max:255',
            'current_zip' => 'nullable|string|max:10',
            'permanent_house_no' => 'nullable|string|max:255',
            'permanent_street' => 'nullable|string|max:255',
            'permanent_barangay' => 'nullable|string|max:255',
            'permanent_city' => 'nullable|string|max:255',
            'permanent_province' => 'nullable|string|max:255',
            'permanent_country' => 'nullable|string|max:255',
            'permanent_zip' => 'nullable|string|max:10',
            'father_first_name' => 'nullable|string|max:255',
            'father_middle_name' => 'nullable|string|max:255',
            'father_last_name' => 'nullable|string|max:255',
            'father_contact' => 'nullable|string|max:20',
            'mother_first_name' => 'nullable|string|max:255',
            'mother_middle_name' => 'nullable|string|max:255',
            'mother_last_name' => 'nullable|string|max:255',
            'mother_contact' => 'nullable|string|max:20',
            'guardian_first_name' => 'nullable|string|max:255',
            'guardian_middle_name' => 'nullable|string|max:255',
            'guardian_last_name' => 'nullable|string|max:255',
            'guardian_contact' => 'nullable|string|max:20',

            'indigenous_people' => 'nullable|string|max:255',
            'fourps_beneficiary' => 'nullable|string|max:255',
            'learner_with_disability' => 'nullable|string|max:255',
        ]);

        $enrollment->update($validated);
        ActivityLogger::log('Update', 'Enrollment', 'Updated enrollment of ' . $enrollment->first_name . ' ' . $enrollment->last_name);

        return redirect()->route('admin.enrollment.index', ['status' => $enrollment->status])
                         ->with('success', 'Student record updated successfully!');
    }

    /**
     * Archive enrollment
     */
    public function archive($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'archived';
        $enrollment->save();

        ActivityLogger::log('Archive', 'Enrollment', 'Archived student ' . $enrollment->first_name . ' ' . $enrollment->last_name);

        return redirect()->route('admin.enrollment.index', ['status' => 'active'])
                         ->with('success', 'Student archived successfully!');
    }

    /**
     * Restore enrollment
     */
    public function restore($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = 'active';
        $enrollment->save();

        ActivityLogger::log('Restore', 'Enrollment', 'Restored student ' . $enrollment->first_name . ' ' . $enrollment->last_name);

        return redirect()->route('admin.enrollment.index', ['status' => 'archived'])
                         ->with('success', 'Student restored successfully!');
    }

    /**
     * Show enrollment details
     */
    public function show($id)
    {
        $enrollment = Enrollment::findOrFail($id);
        return view('pages.admin.enrollment.show', compact('enrollment'));
    }

    /**
     * Upload student document
     */
public function uploadDocument(Request $request, Enrollment $enrollment)
{
    // Validate arrays of files and document IDs
    $request->validate([
        'document_file.*' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        'document_id.*' => 'required|exists:documents,id',
    ]);

    $files = $request->file('document_file');
    $documentIds = $request->document_id;

    foreach ($files as $index => $file) {
        // Skip if file is missing
        if (!$file) continue;

        // Store file
        $fileName = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('student_documents', $fileName, 'public');

        // Create record for each file
        $enrollment->studentDocuments()->create([
            'document_id' => $documentIds[$index],
            'status' => 'Submitted',
            'file_path' => $fileName,
            'submitted_at' => now(),
        ]);

        // Log each uploaded document
        ActivityLogger::log('Upload', 'Document', 'Uploaded ' . $fileName . ' for ' . $enrollment->first_name . ' ' . $enrollment->last_name);
    }

    return back()->with('success', 'Document(s) uploaded successfully.');
}
public function updateMultiple(Request $request, Enrollment $enrollment)
    {
        $studentDocuments = $enrollment->studentDocuments;

        foreach ($studentDocuments as $doc) {

            // Update status if provided
            if (isset($request->statuses[$doc->id])) {
                $doc->status = ucfirst(strtolower(trim($request->statuses[$doc->id])));
            }

            // Replace file if uploaded
            if ($request->hasFile("document_files.{$doc->id}")) {
                // Delete old file if exists
                if ($doc->file_path && Storage::disk('public')->exists($doc->file_path)) {
                    Storage::disk('public')->delete($doc->file_path);
                }

                $doc->file_path = $request->file("document_files.{$doc->id}")
                                    ->store('student_documents', 'public');
            }

            // Update submitted_at only if marking Submitted
            if ($doc->status === 'Submitted' && !$doc->submitted_at) {
                $doc->submitted_at = now();
            }

            $doc->save();

            // Remove duplicate Pending entries
            if ($doc->status === 'Submitted') {
                StudentDocument::where('enrollment_id', $doc->enrollment_id)
                    ->where('document_id', $doc->document_id)
                    ->where('status', 'Pending')
                    ->where('id', '!=', $doc->id)
                    ->delete();
            }
        }

        return redirect()->back()->with('success', 'Documents updated successfully!');
    }
   public function documentsDashboard()
{
    $enrollments = Enrollment::with('studentDocuments')->get();
    $allDocuments = Document::all();

    // ✅ Student population by grade
    $grades = ['Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'];
    $studentsByGrade = [];
    foreach($grades as $grade){
        $studentsByGrade[$grade] = $enrollments->where('grade_level', $grade)->count();
    }

    $monthlyUploads = StudentDocument::selectRaw('MONTH(submitted_at) as month, COUNT(*) as count')
        ->whereNotNull('submitted_at')
        ->whereYear('submitted_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->mapWithKeys(fn($count, $month) => [\Carbon\Carbon::create()->month($month)->format('M') => $count]);

    $yearlyUploads = StudentDocument::selectRaw('YEAR(submitted_at) as year, COUNT(*) as count')
        ->whereNotNull('submitted_at')
        ->groupBy('year')
        ->orderBy('year')
        ->pluck('count', 'year');

    $submissionTrendLabels = collect(range(1, 12))->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('M'))->toArray();

    $submissionTrendData = [];
    foreach ($allDocuments as $doc) {
        $data = StudentDocument::selectRaw('MONTH(submitted_at) as month, COUNT(*) as count')
            ->where('document_id', $doc->id)
            ->whereNotNull('submitted_at')
            ->whereYear('submitted_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $filled = [];
        foreach (range(1, 12) as $m) {
            $filled[] = $data[$m] ?? 0;
        }
        $submissionTrendData[$doc->id] = $filled;
    }

    return view('pages.admin.enrollment.documents-dashboard', compact(
        'enrollments',
        'allDocuments',
        'monthlyUploads',
        'yearlyUploads',
        'submissionTrendLabels',
        'submissionTrendData',
        'studentsByGrade' // ✅ pass it to the view
    ));
}



}


