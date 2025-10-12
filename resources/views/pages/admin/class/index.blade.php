@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fa-solid fa-school-flag me-2"></i> All Classes
        </h2>
        <a href="{{ route('admin.classes.create') }}" class="btn btn-sm btn-success shadow-sm">
            <i class="fa-solid fa-plus"></i> Add Class
        </a>
    </div>

    {{-- Classes Table --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Department</th>
                            <th>Year Level</th>
                            <th>Section</th>
                            <th>Teacher</th>
                            <th>School Year</th>
                            <th>Students</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($classes->currentPage() - 1) * $classes->perPage() + 1; @endphp
                        @foreach ($classes as $class)
                            <tr>
                                <td><span class="fw-semibold text-secondary">{{ $i }}</span></td>
                                <td><span class="fw-semibold text-dark">{{ $class->department }}</span></td>
                                <td><span class="fw-semibold text-primary">{{ $class->year_level }}</span></td>
                                <td>{{ $class->section }}</td>
                                <td><i class="fa-solid fa-user-tie text-secondary me-1"></i>{{ $class->teacher_first_name }} {{ $class->teacher_last_name }}</td>
                                <td><span class="fw-semibold text-dark">{{ $class->year }}</span></td>
                                <td><span class="fw-semibold text-success">{{ $class->students_count }} <i class="fa-solid fa-users ms-1"></i></span></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.classes.show', $class->id) }}"
                                       class="btn btn-sm btn-outline-primary me-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="View Class">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.classes.edit', $class->id) }}"
                                       class="btn btn-sm btn-outline-warning me-1"
                                       data-bs-toggle="tooltip"
                                       data-bs-placement="top"
                                       title="Edit Class">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.classes.destroy', $class->id) }}" method="POST" class="d-inline delete-class-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-class-btn"
                                                data-id="{{ $class->id }}">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
        {{ $classes->links() }}
    </div>
</div>

{{-- Initialize Tooltips and SweetAlert --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // SweetAlert delete confirmation
        const deleteButtons = document.querySelectorAll('.delete-class-btn');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the class!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                })
            });
        });
    });
</script>
@endsection
