@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- ✅ Success Message --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    {{-- ✅ Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fa-solid fa-user-graduate me-2"></i> Students
        </h2>

        <div class="d-flex align-items-center gap-2">
            {{-- ✅ Document Checklist Button --}}
            <a href="{{ route('admin.documents.checklist') }}"
               class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center gap-1"
               data-bs-toggle="tooltip" title="Document Checklist">
               <i class="fa-solid fa-folder-open"></i>
            </a>

            {{-- ✅ Add Enrollment Button --}}
            <a href="{{ route('admin.enrollment.add') }}"
               class="btn btn-primary btn-sm shadow-sm"
               style="width:38px; height:38px; display:flex; align-items:center; justify-content:center;"
               data-bs-toggle="tooltip" title="Add Enrollment">
                <i class="fa-solid fa-plus"></i>
            </a>

            {{-- ✅ Status Dropdown --}}
            <div class="dropdown d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle shadow-sm" type="button"
                        id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ ucfirst($status ?? 'active') }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="statusDropdown">
                    <li><a class="dropdown-item" href="{{ route('admin.enrollment.index', ['status' => 'active']) }}">Active</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.enrollment.index', ['status' => 'archived']) }}">Archived</a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ✅ Enrollments Table --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body">
            <div class="table-responsive">
                <table id="enrollmentTable" class="table table-striped align-middle table-hover">
                    <thead class="table-light text-secondary small text-uppercase">
                        <tr>
                            <th>#</th>
                            <th>School Year</th>
                            <th>LRN</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Birthdate</th>
                            <th>Sex</th>
                            <th>Age</th>
                            <th>Guardian Name</th>
                            <th>Guardian Contact</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($enrollments as $enrollment)
                            @php
                                $requiredCount = count($allDocuments);
                                $uploadedCount = $enrollment->studentDocuments->count();
                                $allComplete = ($uploadedCount >= $requiredCount);
                            @endphp
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>{{ $enrollment->school_year }}</td>
                                <td>{{ $enrollment->lrn }}</td>
                                <td>{{ $enrollment->last_name }}</td>
                                <td>{{ $enrollment->first_name }}</td>
                                <td>{{ $enrollment->middle_name }}</td>
                                <td>{{ $enrollment->birthdate?->format('Y-m-d') ?? '-' }}</td>
                                <td>{{ $enrollment->sex }}</td>
                                <td>{{ $enrollment->age ?? '-' }}</td>
                                <td>{{ $enrollment->guardian_first_name }} {{ $enrollment->guardian_middle_name }} {{ $enrollment->guardian_last_name }}</td>
                                <td>{{ $enrollment->guardian_contact }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.enrollment.show', $enrollment->id) }}"
                                           class="btn btn-outline-info btn-sm rounded-circle"
                                           data-bs-toggle="tooltip" title="View">
                                           <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.enrollment.edit', $enrollment->id) }}"
                                           class="btn btn-outline-warning btn-sm rounded-circle"
                                           data-bs-toggle="tooltip" title="Edit">
                                           <i class="fa-solid fa-pen"></i>
                                        </a>

                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-circle"
                                                data-bs-toggle="modal" data-bs-target="#documentsModal{{ $enrollment->id }}"
                                                title="Documents">
                                            <i class="fa-solid fa-file-lines"></i>
                                        </button>

                                        @if(($status ?? '') !== 'archived')
                                            <form action="{{ route('admin.enrollment.archive', $enrollment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm rounded-circle archive-btn"
                                                    title="Archive">
                                                    <i class="fa-solid fa-box-archive"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.enrollment.restore', $enrollment->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                    class="btn btn-outline-success btn-sm rounded-circle restore-btn"
                                                    title="Restore">
                                                    <i class="fa-solid fa-box-open"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    {{-- ✅ Documents Modal --}}
                                    <div class="modal fade" id="documentsModal{{ $enrollment->id }}" tabindex="-1" aria-labelledby="documentsModalLabel{{ $enrollment->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div class="modal-content rounded-4 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                    <h5 class="modal-title" id="documentsModalLabel{{ $enrollment->id }}">
                                                        <i class="fa-solid fa-file-lines me-2"></i> Completed Documents - {{ $enrollment->first_name }} {{ $enrollment->last_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body p-4">
                                                    <div class="row g-4">
                                                        {{-- Left: Completed Documents --}}
                                                        <div class="col-md-6">
                                                            <h6 class="text-secondary mb-3">Completed Documents</h6>
                                                            @php
                                                                $completedDocs = $enrollment->studentDocuments->filter(fn($doc) => ($doc->status ?? '') === 'Complete');
                                                            @endphp
                                                            @if($completedDocs->count() > 0)
                                                                <ul class="list-group list-group-flush">
                                                                    @foreach($completedDocs as $studentDocument)
                                                                        @php
                                                                            $fileUrl = asset('storage/student_documents/' . basename($studentDocument->file_path));
                                                                        @endphp
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center hover-shadow rounded-3 mb-2 p-3">
                                                                            <div>
                                                                                <i class="fa-solid fa-file-lines me-2 text-primary"></i>
                                                                                <a href="#" class="uploaded-file-link" data-file-url="{{ $fileUrl }}">
                                                                                    {{ $studentDocument->document->name ?? 'Unknown Document' }}
                                                                                </a>
                                                                            </div>
                                                                            <span class="badge bg-success rounded-pill">
                                                                                <i class="fa-solid fa-check me-1"></i> Complete
                                                                            </span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <div class="text-center text-muted py-5 border rounded-3">
                                                                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i>
                                                                    <div>No completed documents uploaded for this student.</div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Right: Upload New Document --}}
                                                        <div class="col-md-6">
                                                            <h6 class="text-secondary mb-3">Upload New Document</h6>
                                                            <form action="{{ route('admin.enrollment.uploadDocument', $enrollment->id) }}"
                                                                  method="POST" enctype="multipart/form-data"
                                                                  id="uploadDocumentForm{{ $enrollment->id }}">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="document_file_{{ $enrollment->id }}" class="form-label">Choose File</label>
                                                                    <input class="form-control form-control-sm rounded-3"
                                                                           type="file"
                                                                           name="document_file"
                                                                           id="document_file_{{ $enrollment->id }}"
                                                                           required
                                                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                                                                    <div class="mt-2" id="previewContainer{{ $enrollment->id }}"></div>
                                                                    @error('document_file')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="document_id_{{ $enrollment->id }}" class="form-label">Document Type</label>
                                                                    <select class="form-select form-select-sm rounded-3" name="document_id" id="document_id_{{ $enrollment->id }}" required>
                                                                        <option value="" selected disabled>Select Document</option>
                                                                        @foreach($allDocuments as $doc)
                                                                            <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('document_id')
                                                                        <span class="text-danger small">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                <button type="submit" class="btn btn-gradient-primary btn-sm w-100">
                                                                    <i class="fa-solid fa-upload me-1"></i> Upload
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- ✅ End Documents Modal --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- ✅ DataTables + Bootstrap CSS/JS --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.table th { font-weight: 600; letter-spacing: .03em; }
.table td, .table th { vertical-align: middle !important; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f9fafb; }
.table-hover tbody tr:hover { background-color: #eef2ff !important; transition: 0.2s ease; }
.btn-sm.rounded-circle { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; }
.dataTables_filter input { border-radius: 8px; border: 1px solid #ced4da; padding: .375rem .75rem; }
.dataTables_info, .dataTables_paginate { font-size: .875rem; }
.hover-shadow:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important; transition: 0.3s ease; }
.btn-gradient-primary {
    background: linear-gradient(135deg, #4e73df, #224abe);
    color: #fff;
    border: none;
    transition: 0.3s;
}
.btn-gradient-primary:hover {
    background: linear-gradient(135deg, #224abe, #4e73df);
    color: #fff;
}
.file-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8f9fa;
    padding: 6px 10px;
    border-radius: 6px;
    margin-bottom: 6px;
    font-size: 0.875rem;
}
.file-preview i { margin-right: 6px; }
.file-preview button { border: none; background: none; color: #dc3545; cursor: pointer; }
</style>

<script>
$(document).ready(function() {
    $('#enrollmentTable').DataTable({
        pageLength: -1,
        responsive: true,
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

document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Archive / Restore buttons
    document.querySelectorAll('.archive-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Archive this Student?',
                text: 'This record will be moved to archived Students.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6c757d',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive it!',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

    document.querySelectorAll('.restore-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Restore this Student?',
                text: 'This record will be moved back to active Students.',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });

    // File upload previews
    document.querySelectorAll('form[id^="uploadDocumentForm"]').forEach(form => {
        const fileInput = form.querySelector('input[type="file"]');
        const previewContainer = form.querySelector('div[id^="previewContainer"]');

        fileInput.addEventListener('change', function() {
            previewContainer.innerHTML = '';
            const file = this.files[0];
            if (!file) return;

            const fileExt = file.name.split('.').pop().toLowerCase();
            let iconClass = 'fa-file-lines';

            const previewDiv = document.createElement('div');
            previewDiv.classList.add('file-preview');
            previewContainer.appendChild(previewDiv);

            if (['jpg','jpeg','png','gif'].includes(fileExt)) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<div style="display:flex; align-items:center;">
                                                <img src="${e.target.result}" style="width:50px; height:50px; object-fit:cover; border-radius:4px; margin-right:8px;">
                                                <div>${file.name}</div>
                                            </div>
                                            <button type="button" title="Remove">&times;</button>`;
                    previewDiv.querySelector('button').addEventListener('click', function() {
                        fileInput.value = '';
                        previewContainer.innerHTML = '';
                    });
                };
                reader.readAsDataURL(file);
            } else if (['pdf'].includes(fileExt)) iconClass = 'fa-file-pdf';
            else if (['doc','docx'].includes(fileExt)) iconClass = 'fa-file-word';

            if (!['jpg','jpeg','png','gif'].includes(fileExt)) {
                previewDiv.innerHTML = `<div><i class="fa-solid ${iconClass}"></i> ${file.name}</div>
                                        <button type="button" title="Remove">&times;</button>`;
                previewDiv.querySelector('button').addEventListener('click', function() {
                    fileInput.value = '';
                    previewContainer.innerHTML = '';
                });
            }

            form.addEventListener('submit', function(e) {
                if (!fileInput.files.length) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'No File Selected',
                        text: 'Please choose a file to upload before submitting.',
                    });
                }
            });
        });
    });

    // Uploaded documents click preview
    document.querySelectorAll('.uploaded-file-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const fileUrl = this.dataset.fileUrl;
            let content = '';
            if(fileUrl.match(/\.(jpg|jpeg|png|gif)$/i)) {
                content = `<img src="${fileUrl}" style="width:100%; max-height:90vh; object-fit:contain;" />`;
            } else if(fileUrl.endsWith('.pdf')) {
                content = `<iframe src="${fileUrl}" style="width:100%; height:90vh;" frameborder="0"></iframe>`;
            } else {
                content = `<div style="text-align:center; font-size:1rem;">Preview not available for this file type.<br><a href="${fileUrl}" target="_blank">Download file</a></div>`;
            }
            Swal.fire({
                html: content,
                width: '90%',
                showCloseButton: true,
                showConfirmButton: false,
                focusConfirm: false,
                scrollbarPadding: false,
            });
        });
    });
});
</script>
@endsection
