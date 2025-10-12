@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">👨‍🏫 {{ $teacher->first_name ?? '' }} {{ $teacher->last_name ?? '' }}'s Profile</h2>
        <p class="text-muted">Personal and professional information at a glance</p>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-lg border-0 text-center p-4 rounded-4 glass-card">
                <img src="{{ in_array($teacher->salutation, ['Mr.', 'Dr.']) ? 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp' : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2.webp' }}"
                     alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 150px;">
                <h4 class="fw-bold">{{ $teacher->first_name ?? '' }} {{ $teacher->middle_name ?? '' }} {{ $teacher->last_name ?? '' }}</h4>
                <span class="badge bg-primary mb-3">{{ $teacher->salutation ?? 'Teacher' }}</span>

                <div class="d-grid gap-2">
                    <a href="{{ route('teacher.settings') }}" class="btn btn-warning btn-sm">Change Password</a>
                </div>

                <div class="mt-3 text-start">
                    <p class="text-muted mb-1">
                        <i class="fas fa-envelope me-2"></i>Login User ID:
                        <strong>{{ $teacher->user->user_id ?? 'N/A' }}</strong>
                    </p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Joined:
                        <strong>{{ $teacher->created_at ? $teacher->created_at->format('F j, Y') : 'N/A' }}</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- Teacher Details --}}
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-lg border-0 p-4 rounded-4 glass-card">
                <h5 class="fw-bold mb-4">Personal Information</h5>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-id-card me-2"></i>Full Name</div>
                    <div class="col-sm-8 text-muted">{{ $teacher->initials ?? '' }} {{ $teacher->first_name ?? '' }} {{ $teacher->middle_name ?? '' }} {{ $teacher->last_name ?? '' }}</div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-map-marker-alt me-2"></i>Address</div>
                    <div class="col-sm-8 text-muted">{{ $teacher->address ?? 'N/A' }}</div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-birthday-cake me-2"></i>DOB</div>
                    <div class="col-sm-8 text-muted">{{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->format('F j, Y') : 'N/A' }}</div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-user-clock me-2"></i>Age</div>
                    <div class="col-sm-8 text-muted">{{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->age : 'N/A' }}</div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-venus-mars me-2"></i>Gender</div>
                    <div class="col-sm-8 text-muted">{{ $teacher->gender ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Collapsible Sections --}}
    <div class="row mt-5 g-4">
        {{-- Assigned Classes --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white"
                     data-bs-toggle="collapse" href="#classesCollapse" role="button" aria-expanded="true"
                     aria-controls="classesCollapse" style="cursor: pointer;">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Assigned Classes</h5>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div id="classesCollapse" class="collapse show">
                    <ul class="list-group list-group-flush">
                        @forelse($teacher->classes as $class)
                            <li class="list-group-item">{{ $class->department ?? '' }} {{ $class->year_level ?? '' }} {{ $class->section ?? '' }}</li>
                        @empty
                            <li class="list-group-item text-muted">No classes assigned</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        {{-- Assigned Subjects --}}
        <div class="col-lg-6 col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header d-flex justify-content-between align-items-center bg-success text-white"
                     data-bs-toggle="collapse" href="#subjectsCollapse" role="button" aria-expanded="true"
                     aria-controls="subjectsCollapse" style="cursor: pointer;">
                    <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Assigned Subjects</h5>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div id="subjectsCollapse" class="collapse show">
                    <div class="table-responsive p-3">
                        <table class="table table-hover table-striped mb-0">
                            <thead>
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
                                        <td>{{ $subject->code ?? 'N/A' }}</td>
                                        <td>{{ $subject->name ?? 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted text-center">No subjects assigned</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.title = "Teacher Profile | Student Management System";

    // Toggle collapse icon rotation
    const collapseHeaders = document.querySelectorAll('.card-header[data-bs-toggle="collapse"]');
    collapseHeaders.forEach(header => {
        const icon = header.querySelector('i.fas.fa-chevron-down');
        const target = document.querySelector(header.getAttribute('href'));
        target.addEventListener('shown.bs.collapse', () => { icon.classList.add('rotate-180'); });
        target.addEventListener('hidden.bs.collapse', () => { icon.classList.remove('rotate-180'); });
    });
</script>

<style>
    /* Glass Card Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 1.5rem !important;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    /* Collapse icon rotation */
    .rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.3s ease;
    }

    /* Table row hover */
    table.table-hover tbody tr:hover {
        background-color: #f0f8ff;
    }

    /* Badge styling */
    .badge {
        font-size: 0.85rem;
        padding: 0.5em 0.75em;
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .card-body ul.list-group li {
            font-size: 0.9rem;
        }
        .card-body table {
            font-size: 0.85rem;
        }
        .fw-bold {
            font-size: 0.95rem;
        }
    }
</style>
@endsection
