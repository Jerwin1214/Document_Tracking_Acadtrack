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
            {{-- Add Student --}}
            <a href="{{ route('admin.enrollment.add') }}" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> Add Student
            </a>

            {{-- Dropdown for Active / Archived --}}
            <div class="dropdown">
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
                <table id="enrollmentTable" class="table table-striped align-middle">
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
                                        {{-- Edit --}}
                                        <a href="{{ route('admin.enrollment.edit', $enrollment->id) }}"
                                           class="btn btn-outline-warning btn-sm rounded-circle"
                                           data-bs-toggle="tooltip" title="Edit">
                                           <i class="fa-solid fa-pen"></i>
                                        </a>

                                        {{-- Archive / Restore --}}
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="mt-3">
                    {{ $enrollments->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ✅ DataTables --}}
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

{{-- ✅ Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
.table th { font-weight: 600; letter-spacing: .03em; }
.table td, .table th { vertical-align: middle !important; }
.table-striped tbody tr:nth-of-type(odd) { background-color: #f9fafb; }
.table-hover tbody tr:hover { background-color: #eef2ff !important; transition: 0.2s ease; }
.btn-sm.rounded-circle { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; }
.dataTables_filter input { border-radius: 8px; border: 1px solid #ced4da; padding: .375rem .75rem; }
.dataTables_length select { border-radius: 8px; padding: .375rem .75rem; }
.dataTables_info, .dataTables_paginate { font-size: .875rem; }
</style>

<script>
$(document).ready(function() {
    $('#enrollmentTable').DataTable({
        pageLength: 10,
        responsive: true,
        language: {
            search: "",
            searchPlaceholder: "Search Student...",
            lengthMenu: "_MENU_ entries per page",
            info: "Showing _START_–_END_ of _TOTAL_ records",
            paginate: { previous: "‹", next: "›" }
        },
        dom: '<"d-flex justify-content-between align-items-center mb-3"lf>tip'
    });
});

// ✅ Make dropdown links work inside DataTables
$(document).on('click', '.dropdown-item', function(e) {
    e.preventDefault();
    window.location.assign($(this).attr('href'));
});


// ✅ Automatically update dropdown button text
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const currentStatus = urlParams.get('status') || 'active';
    document.getElementById('statusDropdown').textContent = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
});
</script>

{{-- ✅ Tooltips + SweetAlert --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Archive confirmation
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
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Restore confirmation
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
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>
@endsection
