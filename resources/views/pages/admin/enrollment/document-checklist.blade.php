@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- ✅ Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fa-solid fa-file-lines me-2"></i> Student Document Checklist
        </h2>
    </div>

    {{-- ✅ Documents Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="documentChecklistTable" class="table table-striped align-middle table-hover">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>LRN</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            @foreach($allDocuments as $doc)
                                <th class="text-center">
                                    {{ str_contains(strtolower($doc->name), 'card') ? 'Card' : $doc->name }}
                                </th>
                            @endforeach
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enrollment->lrn }}</td>
                                <td>{{ $enrollment->last_name }}</td>
                                <td>{{ $enrollment->first_name }}</td>
                                <td>{{ $enrollment->middle_name }}</td>

                                @foreach($allDocuments as $doc)
                                    @php
                                        $studentDoc = $enrollment->studentDocuments
                                            ->where('document_id', $doc->id)
                                            ->sortByDesc('id')
                                            ->first();
                                    @endphp
                                    <td class="text-center">
                                        @if($studentDoc && $studentDoc->status === 'Complete')
                                            <span class="badge bg-success text-white">Complete</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    </td>
                                @endforeach

                                {{-- ✅ Actions --}}
                                <td class="text-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal"
                                            data-bs-target="#editDocumentModal{{ $enrollment->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editDocumentModal{{ $enrollment->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Documents for {{ $enrollment->first_name }} {{ $enrollment->last_name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.documents.updateMultiple', $enrollment->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        @foreach($enrollment->studentDocuments as $studentDoc)
                                                            <div class="mb-3 d-flex align-items-center gap-3">
                                                                <div>
                                                                    <strong>{{ $studentDoc->document->name }}</strong>
                                                                    <span class="badge {{ $studentDoc->status === 'Complete' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                                        {{ $studentDoc->status }}
                                                                    </span><br>
                                                                   @if($studentDoc->file_path)
    @php
        $fileUrl = asset('storage/student_documents/' . basename($studentDoc->file_path));
    @endphp
    <small>Current:
        <a href="{{ $fileUrl }}" target="_blank">View</a>
    </small>
@endif
                                                                </div>
                                                                <div class="flex-grow-1">
                                                                    <label>Replace Document</label>
                                                                    <input type="file" name="document_files[{{ $studentDoc->id }}]" class="form-control">
                                                                </div>
                                                                <div>
                                                                    <label>Status</label>
                                                                    <select name="statuses[{{ $studentDoc->id }}]" class="form-select">
                                                                        <option value="Pending" {{ $studentDoc->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                                        <option value="Complete" {{ $studentDoc->status === 'Complete' ? 'selected' : '' }}>Complete</option>
                                                                        <option value="Missing" {{ $studentDoc->status === 'Missing' ? 'selected' : '' }}>Missing</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Documents</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-start mt-4">
        <a href="{{ route('admin.enrollment.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
</div>

{{-- ✅ DataTables + Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#documentChecklistTable').DataTable({
        responsive: true,
        pageLength: 25,
        lengthChange: false,
        language: {
            search: "",
            searchPlaceholder: "Search Student...",
            info: "Showing _TOTAL_ records",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"f>tip'
    });
});
</script>

<style>
.table th { font-weight: 600; letter-spacing: .03em; }
.table td, .table th { vertical-align: middle !important; text-align: center; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f9fafb; }
.table-hover tbody tr:hover { background-color: #eef2ff !important; transition: 0.2s ease; }
</style>
@endsection
