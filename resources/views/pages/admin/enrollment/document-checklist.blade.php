@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fa-solid fa-file-lines me-2"></i> Student Document Checklist
        </h2>
    </div>
    <div class="mb-3">
    <a href="{{ route('admin.documents.checklist.pdf', ['grade' => request('grade')]) }}"
       target="_blank"
       class="btn btn-sm btn-outline-primary">
       <i class="fa-solid fa-file-pdf"></i> Print PDF
    </a>
</div>


    {{-- Grade Filter --}}
    <div class="mb-3">
        <label for="gradeFilter" class="form-label fw-bold">Filter by Grade Level:</label>
        <select id="gradeFilter" class="form-select w-auto">
            <option value="">All Grades</option>
            @foreach(['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $grade)
                <option value="{{ $grade }}">{{ $grade }}</option>
            @endforeach
        </select>
    </div>

    {{-- Documents Table --}}
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
                            <th>Grade Level</th>
                            @foreach($allDocuments as $doc)
                                <th class="text-center">{{ str_contains(strtolower($doc->name), 'card') ? 'Card' : $doc->name }}</th>
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
                            <td>{{ $enrollment->grade_level }}</td>

                            {{-- Document Status --}}
                            @foreach($allDocuments as $doc)
                                @php
                                    $studentDoc = $enrollment->studentDocuments
                                        ->where('document_id', $doc->id)
                                        ->sortByDesc('id')
                                        ->first();
                                @endphp
                                <td class="text-center">
                                    @if($studentDoc)
                                        <span class="badge status-badge" data-status="{{ $studentDoc->status }}">
                                            {{ $studentDoc->status }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary text-white">No Record</span>
                                    @endif
                                </td>
                            @endforeach

                            {{-- Actions --}}
                            <td class="text-center">
                                <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#viewDocumentModal{{ $enrollment->id }}">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>

                        {{-- View Modal --}}
                        <div class="modal fade" id="viewDocumentModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="viewDocumentModalLabel{{ $enrollment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content rounded-4 shadow border-0">
                                    <div class="modal-header border-0 bg-white">
                                        <h5 class="modal-title">
                                            <i class="fa-solid fa-file-lines text-primary me-2"></i>
                                            Submitted Documents - {{ $enrollment->first_name }} {{ $enrollment->last_name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @foreach($allDocuments as $doc)
                                            @php
                                                $studentDoc = $enrollment->studentDocuments
                                                    ->where('document_id', $doc->id)
                                                    ->where('status', 'Submitted')
                                                    ->sortByDesc('id')
                                                    ->first();
                                                $fileUrl = $studentDoc && $studentDoc->file_path ? asset('storage/student_documents/'.$studentDoc->file_path) : null;
                                            @endphp
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">{{ $doc->name }}</label>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if($fileUrl)
                                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary btn-sm">View</a>
                                                        <span class="text-muted small">{{ basename($studentDoc->file_path) }}</span>
                                                    @else
                                                        <span class="text-muted">Not submitted yet</span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="modal-footer border-0 pt-2">
                                        <button type="button" class="btn btn-light btn-sm rounded-3" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

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

{{-- DataTables + SweetAlert Scripts --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // SweetAlert success popup
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif

    const table = $('#documentChecklistTable').DataTable({
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

    function updateBadgeColors() {
        $('.status-badge').each(function() {
            const status = $(this).data('status');
            $(this).removeClass('bg-success bg-warning bg-danger text-white text-dark');
            if (status === 'Submitted') $(this).addClass('bg-success text-white');
            else if (status === 'Pending') $(this).addClass('bg-warning text-dark');
            else if (status === 'Missing') $(this).addClass('bg-danger text-white');
        });
    }
    updateBadgeColors();
    table.on('draw', updateBadgeColors);

    $('#gradeFilter').on('change', function() {
        const selectedGrade = $(this).val();
        table.column(5).search(selectedGrade ? '^' + selectedGrade + '$' : '', true, false).draw();
    });
});
</script>

<style>
.table th { font-weight: 600; letter-spacing: .03em; }
.table td, .table th { vertical-align: middle !important; text-align: center; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f9fafb; }
.table-hover tbody tr:hover { background-color: #eef2ff !important; transition: 0.2s ease; }

.badge.bg-danger { background-color: #dc3545 !important; }
.badge.bg-success { background-color: #198754 !important; }
.badge.bg-warning { background-color: #ffc107 !important; color: #000 !important; }

.modal-content { transition: all 0.2s ease-in-out; }
.modal-header h5 { font-weight: 600; font-size: 1rem; }
.modal-footer button { min-width: 90px; }
</style>
@endsection
