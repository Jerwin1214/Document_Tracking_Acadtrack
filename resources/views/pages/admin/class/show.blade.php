@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fa-solid fa-chalkboard me-2"></i>Class Section Details</h2>
        <h5 class="fw-semibold text-secondary">Academic Year: {{ $class->year }}</h5>
    </div>

    {{-- Class Section Info --}}
    <div class="card shadow-sm mb-4 rounded-3">
        <div class="card-body">
            <h4 class="fw-semibold">Section: {{ $class->section }}</h4>
            <p><strong>Department:</strong> {{ $class->department }}</p>
            <p><strong>Year Level:</strong> {{ $class->year_level }}</p>
            <p><strong>Class Teacher:</strong>
                @if($class->teacher)
                    {{ $class->teacher->first_name }} {{ $class->teacher->last_name }}
                @else
                    -
                @endif
            </p>
        </div>
    </div>

    {{-- Subjects Assigned --}}
    <div class="card shadow-sm mb-4 rounded-3">
        <div class="card-header bg-light">
            <h5 class="mb-0">Subjects Assigned to
                @if($class->teacher)
                    {{ $class->teacher->first_name }}
                @else
                    the Teacher
                @endif
            </h5>
        </div>
        <div class="card-body">
            @if($subjects->count() > 0)
                <ul class="list-group list-group-flush">
                    @foreach($subjects as $subject)
                        <li class="list-group-item">{{ $subject->name }} <span class="badge bg-secondary ms-2">{{ $subject->code ?? '' }}</span></li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No subjects assigned to this teacher.</p>
            @endif
        </div>
    </div>

    {{-- Students Table --}}
    <div class="card shadow-sm mb-4 rounded-3">
        <div class="card-header bg-light">
            <h5 class="mb-0">Students in Section {{ $class->section }}</h5>
        </div>
        <div class="card-body table-responsive">
            @if($students->count() > 0)
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Guardian</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i = 1; @endphp
                    @foreach($students as $student)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $student->student_id }}</td>
                        <td>{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->gender }}</td>
                        <td>
                            @if($student->guardian)
                                {{ $student->guardian->first_name }} {{ $student->guardian->last_name }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.students.show', $student->id) }}"
                               class="btn btn-sm btn-outline-primary"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="View Student">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @php $i++; @endphp
                    @endforeach
                </tbody>
            </table>
            @else
                <p class="text-muted">No students assigned to this section yet.</p>
            @endif
        </div>
    </div>

    {{-- Back Button --}}
    <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary shadow-sm">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Classes
    </a>
</div>

{{-- Initialize tooltips --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@endsection
