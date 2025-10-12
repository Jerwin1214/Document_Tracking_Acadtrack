<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Document;
use App\Models\StudentDocument;
use Illuminate\Support\Facades\Storage;

class StudentDocumentController extends Controller
{
    /**
     * Show documents for a specific enrollment
     */
    public function index(Enrollment $enrollment)
    {
        $documents = Document::all();

        foreach ($documents as $doc) {
            $completed = StudentDocument::where('enrollment_id', $enrollment->id)
                                        ->where('document_id', $doc->id)
                                        ->where('status', 'Complete')
                                        ->first();

            if ($completed) {
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
            'status' => 'required|in:Complete,Missing,Pending',
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

        // Update submitted_at only if marking Complete
        if ($data['status'] === 'Complete' && empty($studentDocument->submitted_at)) {
            $data['submitted_at'] = now();
        }

        $studentDocument->update($data);

        // Remove pending duplicates for same document
        if ($data['status'] === 'Complete') {
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
                $completed = StudentDocument::where('enrollment_id', $enrollment->id)
                                            ->where('document_id', $doc->id)
                                            ->where('status', 'Complete')
                                            ->first();

                if ($completed) {
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

    /**
     * Dashboard view with charts
     */
  public function dashboard()
    {
        // ✅ Get all documents and submissions
        $allDocuments = Document::all();
        $studentDocuments = StudentDocument::where('status', 'Complete')
            ->whereNotNull('submitted_at')
            ->get();

        // ✅ Monthly uploads (Jan–Dec)
        $monthlyUploads = [];
        foreach (range(1, 12) as $month) {
            $monthlyUploads[date('M', mktime(0, 0, 0, $month, 1))] =
                $studentDocuments->filter(fn($d) => date('n', strtotime($d->submitted_at)) == $month)->count();
        }

        // ✅ Yearly uploads
        $yearlyUploads = [];
        foreach ($studentDocuments->groupBy(fn($d) => date('Y', strtotime($d->submitted_at))) as $year => $docs) {
            $yearlyUploads[$year] = $docs->count();
        }

        // ✅ Submission trends (per document per month)
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


    /**
     * Delete a student document
     */
    public function destroy(StudentDocument $studentDocument)
    {
        // Delete the file from storage if exists
        if ($studentDocument->file_path && Storage::disk('public')->exists($studentDocument->file_path)) {
            Storage::disk('public')->delete($studentDocument->file_path);
        }

        $studentDocument->delete();

        return back()->with('success', 'Document deleted successfully!');
    }
    public function updateMultiple(Request $request, Enrollment $enrollment)
{
    $studentDocuments = $enrollment->studentDocuments;

    foreach ($studentDocuments as $doc) {

        // Update status if provided
        if(isset($request->statuses[$doc->id])){
            $doc->status = $request->statuses[$doc->id];
        }

        // Replace file if uploaded
        if($request->hasFile("document_files.{$doc->id}")){
            // Delete old file
            if($doc->file_path && Storage::disk('public')->exists($doc->file_path)){
                Storage::disk('public')->delete($doc->file_path);
            }
            $doc->file_path = $request->file("document_files.{$doc->id}")->store('student_documents', 'public');
        }

        // Update submitted_at only if Complete
        if($doc->status === 'Complete' && !$doc->submitted_at){
            $doc->submitted_at = now();
        }

        $doc->save();

        // Remove duplicate pending
        if($doc->status === 'Complete'){
            StudentDocument::where('enrollment_id', $doc->enrollment_id)
                ->where('document_id', $doc->document_id)
                ->where('status', 'Pending')
                ->where('id','!=',$doc->id)
                ->delete();
        }
    }

    return redirect()->back()->with('success', 'Documents updated successfully!');
}

}
