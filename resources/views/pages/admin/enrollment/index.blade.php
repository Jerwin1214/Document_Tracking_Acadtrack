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
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fa-solid fa-user-graduate me-2"></i> Students
        </h2>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            {{-- ✅ Document Checklist Button --}}
            <a href="{{ route('admin.documents.checklist') }}"
               class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center gap-1"
               style="width:38px; height:38px; display:flex; align-items:center; justify-content:center;"
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

    {{-- ✅ Grade Level Filter --}}
    <div class="dropdown mb-3">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle shadow-sm" type="button"
                id="gradeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            {{ $selectedGrade ?? 'All Grades' }}
        </button>
        <ul class="dropdown-menu" aria-labelledby="gradeDropdown">
            <li><a class="dropdown-item" href="{{ route('admin.enrollment.index', ['status' => $status, 'grade' => 'all']) }}">All Grades</a></li>
            @foreach($grades as $grade)
                <li>
                    <a class="dropdown-item" href="{{ route('admin.enrollment.index', ['status' => $status, 'grade' => $grade]) }}">
                        {{ $grade }}
                    </a>
                </li>
            @endforeach
        </ul>
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
                                $uploadedCount = $enrollment->studentDocuments->where('status', 'Submitted')->count();
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
                                    <div class="d-flex justify-content-center gap-2 flex-wrap">
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
                                        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
                                            <div class="modal-content rounded-4 shadow-lg border-0">
                                                <div class="modal-header bg-primary text-white rounded-top-4">
                                                    <h5 class="modal-title" id="documentsModalLabel{{ $enrollment->id }}">
                                                        <i class="fa-solid fa-file-lines me-2"></i> Documents - {{ $enrollment->first_name }} {{ $enrollment->last_name }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <div class="modal-body p-4">
                                                    <div class="row g-4">
                                                        {{-- Left: Submitted Documents --}}
                                                        <div class="col-md-6 col-12">
                                                            <h6 class="text-secondary mb-3">Submitted Documents</h6>
                                                            @php
                                                                $submittedDocuments = $enrollment->studentDocuments->where('status', 'Submitted');
                                                            @endphp
                                                            @if($submittedDocuments->count() > 0)
                                                                <ul class="list-group list-group-flush">
                                                                    @foreach($submittedDocuments as $doc)
                                                                        @php
                                                                            $fileUrl = $doc->file_path ? asset('storage/student_documents/' . basename($doc->file_path)) : null;
                                                                        @endphp
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center hover-shadow rounded-3 mb-2 p-3 flex-wrap">
                                                                            <div class="d-flex flex-column w-100">
                                                                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                                                    <div>
                                                                                        <i class="fa-solid fa-file-lines me-2 text-primary"></i>
                                                                                        {{ $doc->document->name ?? 'Unknown Document' }}
                                                                                    </div>
                                                                                    <span class="badge bg-success">{{ $doc->status }}</span>
                                                                                </div>
                                                                                <div class="mt-2">
                                                                                    @if($fileUrl)
                                                                                        <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">View</a>
                                                                                    @else
                                                                                        <span class="text-muted small">File not available</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <div class="text-center text-muted py-5 border rounded-3">
                                                                    <i class="fa-solid fa-folder-open fa-2x mb-2"></i>
                                                                    <div>No submitted documents yet for this student.</div>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Right: Upload New Document(s) --}}
                                                        <div class="col-md-6 col-12">
                                                            <h6 class="text-secondary mb-3">Upload New Document(s)</h6>
                                                            <form action="{{ route('admin.enrollment.uploadDocument', $enrollment->id) }}" method="POST" enctype="multipart/form-data" id="uploadDocumentForm{{ $enrollment->id }}">
                                                                @csrf
                                                                <div id="document-container-{{ $enrollment->id }}">
                                                                    <div class="document-row mb-3 d-flex gap-2 align-items-start flex-wrap">
                                                                        <input class="form-control form-control-sm rounded-3 flex-grow-1"
                                                                               type="file"
                                                                               name="document_file[]"
                                                                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                                               required>
                                                                        <select class="form-select form-select-sm rounded-3 document-select flex-grow-1" name="document_id[]" required>
                                                                            <option value="" selected disabled>Select Document</option>
                                                                            @foreach($allDocuments as $doc)
                                                                                <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <button type="button" class="btn btn-danger btn-sm remove-row flex-shrink-0" title="Remove">&times;</button>
                                                                    </div>
                                                                </div>

                                                                <button type="button" id="add-document-{{ $enrollment->id }}" class="btn btn-secondary btn-sm mb-3 w-100">
                                                                    <i class="fa-solid fa-plus me-1"></i> Add Another Document
                                                                </button>

                                                                <button type="submit" class="btn btn-gradient-primary btn-sm w-100">
                                                                    <i class="fa-solid fa-upload me-1"></i> Upload Document(s)
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer border-0 pt-0">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-3 w-100" data-bs-dismiss="modal">Close</button>
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

{{-- ✅ Extra mobile responsive styling --}}
<style>
@media (max-width: 768px) {
    .table th, .table td {
        white-space: nowrap;
    }
    .table-responsive {
        overflow-x: auto;
    }
    .modal-body .col-md-6 {
        width: 100%;
    }
}
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
    // Initialize tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

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
            }).then(result => { if (result.isConfirmed) form.submit(); });
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
            }).then(result => { if (result.isConfirmed) form.submit(); });
        });
    });

    // File preview function
    function attachFilePreview(fileInput, previewContainer) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            const fileExt = file.name.split('.').pop().toLowerCase();
            let iconClass = 'fa-file-lines';

            // Remove previous preview for this input
            const existingPreview = fileInput.nextElementSibling;
            if (existingPreview && existingPreview.classList.contains('file-preview')) existingPreview.remove();

            const fileDiv = document.createElement('div');
            fileDiv.classList.add('file-preview', 'd-flex', 'align-items-center', 'gap-2', 'mb-2');
            previewContainer.appendChild(fileDiv);

            if (['jpg','jpeg','png','gif'].includes(fileExt)) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileDiv.innerHTML = `
                        <img src="${e.target.result}" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                        <span>${file.name}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger">&times;</button>
                    `;
                    fileDiv.querySelector('button').addEventListener('click', function() {
                        fileInput.value = '';
                        fileDiv.remove();
                    });
                };
                reader.readAsDataURL(file);
            } else {
                if (['pdf'].includes(fileExt)) iconClass = 'fa-file-pdf';
                else if (['doc','docx'].includes(fileExt)) iconClass = 'fa-file-word';

                fileDiv.innerHTML = `
                    <i class="fa-solid ${iconClass}"></i>
                    <span>${file.name}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger">&times;</button>
                `;
                fileDiv.querySelector('button').addEventListener('click', function() {
                    fileInput.value = '';
                    fileDiv.remove();
                });
            }
        });
    }

    // Attach preview to existing file inputs
    document.querySelectorAll('.document-row input[type="file"]').forEach(fileInput => {
        const enrollmentId = fileInput.closest('form').id.replace('uploadDocumentForm','');
        const previewContainer = document.getElementById(`previewContainer${enrollmentId}`);
        attachFilePreview(fileInput, previewContainer);
    });

    // Show/Hide "Other" input
    document.querySelectorAll('.document-select').forEach(select => {
        select.addEventListener('change', function() {
            const otherInputDiv = document.getElementById(this.dataset.otherInput);
            if (this.value.toLowerCase() === 'other') {
                otherInputDiv.classList.remove('d-none');
                otherInputDiv.querySelector('input').required = true;
            } else {
                otherInputDiv.classList.add('d-none');
                otherInputDiv.querySelector('input').required = false;
            }
        });
    });

    // Dynamic add/remove document rows
    document.querySelectorAll('[id^="add-document-"]').forEach(btn => {
        const enrollmentId = btn.id.split('-')[2];
        const container = document.getElementById(`document-container-${enrollmentId}`);
        const previewContainer = document.getElementById(`previewContainer${enrollmentId}`);

        btn.addEventListener('click', function() {
            const row = document.createElement('div');
            row.classList.add('document-row','mb-3','d-flex','gap-2','align-items-start');
            row.innerHTML = `
                <input class="form-control form-control-sm rounded-3" type="file" name="document_file[]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" required>
                <select class="form-select form-select-sm rounded-3 document-select" name="document_id[]" required>
                    <option value="" selected disabled>Select Document</option>
                    @foreach($allDocuments as $doc)
                        <option value="{{ $doc->id }}">{{ $doc->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-danger btn-sm remove-row" title="Remove">&times;</button>
            `;
            container.appendChild(row);

            // Remove row
            row.querySelector('.remove-row').addEventListener('click', () => row.remove());

            // Attach preview to new file input
            const fileInput = row.querySelector('input[type="file"]');
            attachFilePreview(fileInput, previewContainer);

            // Attach change event for "Other" select if needed
            const selectInput = row.querySelector('.document-select');
            selectInput.addEventListener('change', function() {
                const otherInputDiv = document.getElementById(this.dataset.otherInput);
                if (this.value.toLowerCase() === 'other') {
                    otherInputDiv.classList.remove('d-none');
                    otherInputDiv.querySelector('input').required = true;
                } else if (otherInputDiv) {
                    otherInputDiv.classList.add('d-none');
                    otherInputDiv.querySelector('input').required = false;
                }
            });
        });
    });

    // Uploaded documents preview on click
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
