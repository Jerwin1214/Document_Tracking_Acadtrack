@extends('pages.admin.admin-content')

@section('content')
<div class="container py-5">

    {{-- ✅ Page Title --}}
    <h2 class="fw-bold mb-4">
        👩‍🏫 {{ $teacher->full_name }}
    </h2>

    <div class="row g-4">
        {{-- ✅ Profile Card --}}
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center">
                    <img src="{{ $teacher->salutation == 'Mr.' || $teacher->salutation == 'Dr.'
                        ? 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp'
                        : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2.webp' }}"
                        alt="avatar"
                        class="rounded-circle img-fluid shadow"
                        style="width: 150px;">

                    <h4 class="fw-bold mt-3 mb-1">{{ $teacher->first_name }} {{ $teacher->last_name }}</h4>
                    <p class="text-muted mb-3">{{ $teacher->salutation }}</p>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="/admin/teachers/{{ $teacher->id }}/edit"
                           class="btn btn-warning btn-sm rounded-pill shadow-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>

                        {{-- Archive --}}
                        <form action="{{ route('admin.teachers.archive', $teacher->id) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to archive this teacher?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="btn btn-secondary btn-sm rounded-pill shadow-sm">
                                <i class="fas fa-archive me-1"></i> Archive
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Details Card --}}
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-gradient-primary text-white fw-bold py-3">
                    <i class="fas fa-id-card me-2"></i> Teacher Details
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Full Name</div>
                        <div class="col-sm-9 text-muted">{{ $teacher->full_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Address</div>
                        <div class="col-sm-9 text-muted">{{ $teacher->address }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Date of Birth</div>
                        <div class="col-sm-9 text-muted">{{ $teacher->dob->format('F d, Y') }}</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 fw-semibold">Gender</div>
                        <div class="col-sm-9 text-muted">{{ $teacher->gender }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Assigned Classes & Subjects --}}
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">

                    {{-- Classes --}}
                    <h5 class="fw-bold mb-3"><i class="fas fa-users me-2"></i> Assigned Classes</h5>
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Grade Level</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->classes as $class)
                                <tr>
                                    <td>{{ $class->year_level ?? '-' }}</td>
                                    <td>{{ $class->section ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No classes assigned</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Subjects --}}
                    <h5 class="fw-bold mt-4 mb-3"><i class="fas fa-book me-2"></i> Assigned Subjects</h5>
                    <table class="table table-hover table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teacher->subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subject->code }}</td>
                                    <td>{{ $subject->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">No subjects assigned</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ✅ Custom Styles --}}
<style>
    .bg-gradient-primary {
        background: linear-gradient(45deg, #007bff, #6f42c1);
    }
    .btn {
        transition: 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        opacity: 0.9;
    }
</style>

{{-- ✅ Script --}}
<script>
    $(document).ready(function() {
        document.title = 'Teacher Profile | Student Management System';
    });
</script>
@endsection
