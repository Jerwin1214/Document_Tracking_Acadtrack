<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Document;
use App\Models\StudentDocument;

class EnrollmentController extends Controller
{
    /**
     * Display all enrollments (active or archived)
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $enrollments = Enrollment::where('status', $status)
            ->with(['studentDocuments.document']) // eager load related documents
            ->orderBy('last_name')
            ->get();

        $allDocuments = Document::all(); // fetch all documents

        return view('pages.admin.enrollment.index', compact('enrollments', 'status', 'allDocuments'));
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
            'grade_level_to_enroll' => 'nullable|string|max:255',
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
        ]);

        $validated['status'] = 'active';

        Enrollment::create($validated);

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
            'grade_level_to_enroll' => 'nullable|string|max:255',
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
        ]);

        $enrollment->update($validated);

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
        $request->validate([
            'document_file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'document_id' => 'required|exists:documents,id',
        ]);

        $file = $request->file('document_file');
        $fileName = time().'_'.$file->getClientOriginalName();
        $file->storeAs('student_documents', $fileName, 'public');

        // Save to database using enrollment_id
        $enrollment->studentDocuments()->create([
            'document_id' => $request->document_id,
            'status' => 'Complete',
            'file_path' => $fileName,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }
public function documentsDashboard()
{
    // Load all enrollments with related student documents
    $enrollments = Enrollment::with('studentDocuments')->get();

    // Load all possible document types
    $allDocuments = Document::all();

    // ✅ Monthly uploads (for the current year)
    $monthlyUploads = StudentDocument::selectRaw('MONTH(submitted_at) as month, COUNT(*) as count')
        ->whereNotNull('submitted_at')
        ->whereYear('submitted_at', now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month')
        ->mapWithKeys(function ($count, $month) {
            return [\Carbon\Carbon::create()->month($month)->format('M') => $count];
        });

    // ✅ Yearly uploads (all-time)
    $yearlyUploads = StudentDocument::selectRaw('YEAR(submitted_at) as year, COUNT(*) as count')
        ->whereNotNull('submitted_at')
        ->groupBy('year')
        ->orderBy('year')
        ->pluck('count', 'year');

    // ✅ Submission trend per document (monthly trend for each document)
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

        // Fill missing months with 0
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
        'submissionTrendData'
    ));
}

}
