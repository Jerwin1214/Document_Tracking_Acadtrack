<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Document;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;


class StudentDocumentController extends Controller
{
    /**
     * Show documents for a specific enrollment
     */
public function index(Enrollment $enrollment)
{
    $documents = Document::all();

    foreach ($documents as $doc) {
        $existing = StudentDocument::where('enrollment_id', $enrollment->id)
                                   ->where('document_id', $doc->id)
                                   ->first();

        if (!$existing) {
            // Only create Pending if no record exists at all
            StudentDocument::create([
                'enrollment_id' => $enrollment->id,
                'document_id' => $doc->id,
                'status' => 'Pending',
                'file_path' => null,
                'submitted_at' => null,
                'remarks' => null,
            ]);
        }
    }

    $studentDocuments = StudentDocument::with('document')
                            ->where('enrollment_id', $enrollment->id)
                            ->get();

    return view('pages.admin.students.document-checklist', [
        'enrollment' => $enrollment,
        'studentDocuments' => $studentDocuments,
        'allDocuments' => $documents
    ]);
}


    /**
     * Show edit form for a student's documents
     */
    public function edit(Enrollment $enrollment)
    {
        $studentDocuments = StudentDocument::with('document')
                                ->where('enrollment_id', $enrollment->id)
                                ->get();

        $documents = Document::all();

        return view('pages.admin.students.edit-documents', [
            'enrollment' => $enrollment,
            'studentDocuments' => $studentDocuments,
            'allDocuments' => $documents
        ]);
    }

    /**
     * Update a student document record (replace file or update status/remarks)
     */
    public function update(Request $request, StudentDocument $studentDocument)
    {
        $data = $request->validate([
            'status' => 'required|in:Submitted,Missing,Pending',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remarks' => 'nullable|string|max:500'
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($studentDocument->file_path && Storage::disk('public')->exists($studentDocument->file_path)) {
                Storage::disk('public')->delete($studentDocument->file_path);
            }

            // Store new file
            $data['file_path'] = $request->file('file')->store('student_documents', 'public');
        }

        // Update submitted_at only if marking Submitted
        if ($data['status'] === 'Submitted' && empty($studentDocument->submitted_at)) {
            $data['submitted_at'] = now();
        }

        $studentDocument->update($data);

        // Remove pending duplicates for same document
        if ($data['status'] === 'Submitted') {
            StudentDocument::where('enrollment_id', $studentDocument->enrollment_id)
                           ->where('document_id', $studentDocument->document_id)
                           ->where('status', 'Pending')
                           ->where('id', '!=', $studentDocument->id)
                           ->delete();
        }

        return back()->with('success', 'Document updated successfully!');
    }

    /**
     * Show document checklist for all enrollments
     */
    public function checklist()
    {
        $enrollments = Enrollment::with('studentDocuments')->get();
        $allDocuments = Document::all();

        foreach ($enrollments as $enrollment) {
            foreach ($allDocuments as $doc) {
                $submitted = StudentDocument::where('enrollment_id', $enrollment->id)
                                            ->where('document_id', $doc->id)
                                            ->where('status', 'Submitted')
                                            ->first();

                if ($submitted) {
                    StudentDocument::where('enrollment_id', $enrollment->id)
                                   ->where('document_id', $doc->id)
                                   ->where('status', 'Pending')
                                   ->delete();
                } else {
                    StudentDocument::firstOrCreate(
                        [
                            'enrollment_id' => $enrollment->id,
                            'document_id' => $doc->id
                        ],
                        [
                            'status' => 'Pending',
                            'file_path' => null,
                            'submitted_at' => null,
                            'remarks' => null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]
                    );
                }
            }
        }

        return view('pages.admin.enrollment.document-checklist', compact('enrollments', 'allDocuments'));
    }


public function printReport()
{
    // === Base Data ===
    $enrollments = Enrollment::with('studentDocuments')->get();
    $allDocuments = Document::all();

    $totalStudents = $enrollments->count();

    $submittedDocs = StudentDocument::where('status', 'Submitted')->count();
    $pendingDocs   = StudentDocument::where('status', 'Pending')->count();

    // Pending 1 year and above
    $pendingOverYear = StudentDocument::where('status', 'Pending')
        ->where('created_at', '<=', now()->subYear())
        ->count();

// Near deadline: pending between 335 and 364 days
$nearDeadline = StudentDocument::where('status', 'Pending')
    ->whereBetween('created_at', [now()->subDays(365), now()->subDays(335)])
    ->count();

    $completionRate = ($submittedDocs + $pendingDocs) > 0
        ? round(($submittedDocs / ($submittedDocs + $pendingDocs)) * 100, 2)
        : 0;

    // === Students by Grade ===
    $studentsByGrade = $enrollments->groupBy('grade_level')->map->count();

    // === Documents per Type ===
    $documentsPerType = [];
    foreach ($allDocuments as $doc) {
        $submitted = StudentDocument::where('document_id', $doc->id)->where('status', 'Submitted')->count();
        $pending = StudentDocument::where('document_id', $doc->id)->where('status', 'Pending')->count();
        $total = $submitted + $pending;
        $documentsPerType[$doc->name] = [
            'submitted' => $submitted,
            'pending' => $pending,
            'completion' => $total > 0 ? ($submitted / $total) * 100 : 0
        ];
    }

    // === Pending Duration Overview ===
    $pendingDuration = [
        '0–30 days' => StudentDocument::where('status', 'Pending')
                        ->where('created_at', '>=', now()->subDays(30))->count(),
        '31–90 days' => StudentDocument::where('status', 'Pending')
                        ->whereBetween('created_at', [now()->subDays(90), now()->subDays(31)])->count(),
        '91–180 days' => StudentDocument::where('status', 'Pending')
                        ->whereBetween('created_at', [now()->subDays(180), now()->subDays(91)])->count(),
        '181–365 days' => StudentDocument::where('status', 'Pending')
                        ->whereBetween('created_at', [now()->subDays(365), now()->subDays(181)])->count(),
        '1 year above' => $pendingOverYear,
    ];

    // === Monthly Uploads ===
    $studentDocuments = StudentDocument::where('status', 'Submitted')->whereNotNull('submitted_at')->get();
    $monthlyUploads = [];
    foreach (range(1, 12) as $month) {
        $monthlyUploads[date('M', mktime(0, 0, 0, $month, 1))] =
            $studentDocuments->filter(fn($d) => date('n', strtotime($d->submitted_at)) == $month)->count();
    }

    // === Yearly Uploads ===
    $yearlyUploads = [];
    foreach ($studentDocuments->groupBy(fn($d) => date('Y', strtotime($d->submitted_at))) as $year => $docs) {
        $yearlyUploads[$year] = $docs->count();
    }

    // === Submission Trend per Document per Month ===
    $submissionTrendLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    $submissionTrendData = [];
    foreach ($allDocuments as $doc) {
        $monthlyCounts = array_fill(1, 12, 0);
        foreach ($studentDocuments->where('document_id', $doc->id) as $sd) {
            $month = (int) date('n', strtotime($sd->submitted_at));
            $monthlyCounts[$month]++;
        }
        $submissionTrendData[$doc->id] = array_values($monthlyCounts);
    }

    // === Generate PDF ===
$pdf = Pdf::loadView('pages.admin.dashboard-pdf', compact(
    'totalStudents',
    'submittedDocs',
    'pendingDocs',
    'pendingOverYear',
    'nearDeadline',
    'completionRate',
    'studentsByGrade',
    'documentsPerType',
    'pendingDuration',
    'monthlyUploads',
    'yearlyUploads',
    'submissionTrendLabels',
    'submissionTrendData',
    'allDocuments'
))->setPaper('a4', 'portrait');


    return $pdf->stream('dashboard-report.pdf');

}

    /**
     * Dashboard view with charts
     */
    public function dashboard()
    {
        $allDocuments = Document::all();
        $studentDocuments = StudentDocument::where('status', 'Submitted')
            ->whereNotNull('submitted_at')
            ->get();

        // Monthly uploads (Jan–Dec)
        $monthlyUploads = [];
        foreach (range(1, 12) as $month) {
            $monthlyUploads[date('M', mktime(0, 0, 0, $month, 1))] =
                $studentDocuments->filter(fn($d) => date('n', strtotime($d->submitted_at)) == $month)->count();
        }

        // Yearly uploads
        $yearlyUploads = [];
        foreach ($studentDocuments->groupBy(fn($d) => date('Y', strtotime($d->submitted_at))) as $year => $docs) {
            $yearlyUploads[$year] = $docs->count();
        }

        // Submission trends (per document per month)
        $submissionTrendLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $submissionTrendData = [];

        foreach ($allDocuments as $doc) {
            $monthlyCounts = array_fill(1, 12, 0);
            foreach ($studentDocuments->where('document_id', $doc->id) as $sd) {
                $month = (int) date('n', strtotime($sd->submitted_at));
                $monthlyCounts[$month]++;
            }
            $submissionTrendData[$doc->id] = array_values($monthlyCounts);
        }

        return view('pages.admin.dashboard', compact(
            'allDocuments',
            'monthlyUploads',
            'yearlyUploads',
            'submissionTrendLabels',
            'submissionTrendData'
        ));
    }

    public function printChecklist(Request $request)
{
    $gradeFilter = $request->query('grade');

    $enrollments = Enrollment::with(['studentDocuments.document'])
        ->when($gradeFilter, fn($q) => $q->where('grade_level', $gradeFilter))
        ->get();

    $allDocuments = Document::all();

    $totalStudents = $enrollments->count();

$pdf = Pdf::loadView('pages.admin.documents.document-checklist-pdf', [
    'enrollments' => $enrollments,
    'allDocuments' => $allDocuments,
    'totalStudents' => $totalStudents,
    'grade' => $gradeFilter  // ✅ Pass selected grade
])->setPaper('a4', 'landscape');


    return $pdf->stream('student-document-checklist.pdf');
}


    /**
     * Delete a student document
   */
    // public function destroy(StudentDocument $studentDocument)
    // {
    //     if ($studentDocument->file_path && Storage::disk('public')->exists($studentDocument->file_path)) {
    //         Storage::disk('public')->delete($studentDocument->file_path);
    //     }

    //     $studentDocument->delete();

    //     return back()->with('success', 'Document deleted successfully!');
    // }

    /**
     * Update multiple documents at once
     */

//    public function uploadDocument(Request $request, $studentDocumentId)
// {
//     $request->validate([
//         'document' => 'required|file|mimes:pdf,jpg,png|max:10240',
//     ]);

//     $studentDocument = StudentDocument::findOrFail($studentDocumentId);

//     if ($request->hasFile('document')) {
//         if ($studentDocument->file_path && Storage::disk('public')->exists($studentDocument->file_path)) {
//             Storage::disk('public')->delete($studentDocument->file_path);
//         }

//         $file = $request->file('document');
//         $fileName = uniqid() . '_' . $file->getClientOriginalName();
//         $filePath = $file->storeAs('student_documents', $fileName, 'public');

//         $studentDocument->file_path = $filePath;
//         $studentDocument->status = 'Submitted';
//         $studentDocument->submitted_at = now();
//         $studentDocument->save();

//         return back()->with('success', 'Document uploaded successfully!');
//     }

//     return back()->with('error', 'No document was uploaded.');
// }


}
