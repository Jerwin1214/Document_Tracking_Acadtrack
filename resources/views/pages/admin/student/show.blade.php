@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">
    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">👨‍🎓 {{ $student->first_name }}'s Profile</h2>
    </div>

    <div class="row">
        <!-- Profile Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                         alt="avatar"
                         class="rounded-circle img-fluid shadow-sm mb-3"
                         style="width: 150px; height: 150px; object-fit: cover;">

                    <h5 class="fw-bold mb-1">{{ $student->first_name }} {{ $student->last_name }}</h5>
                    <span class="badge {{ $student->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($student->status) }}
                    </span>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-center mt-3">
                        <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm shadow-sm">✏️ Edit</a>

                        @if ($student->status === 'active')
                        <form action="{{ route('admin.students.archive', $student->id) }}" method="POST" class="ms-2"
                              onsubmit="return confirm('Are you sure you want to archive this student?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-secondary btn-sm shadow-sm">📦 Archive</button>
                        </form>
                        @else
                        <form action="{{ route('admin.students.unarchive', $student->id) }}" method="POST" class="ms-2"
                              onsubmit="return confirm('Are you sure you want to unarchive this student?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm shadow-sm">♻️ Unarchive</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Details -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📋 Student Information</h5>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Full Name</div>
                        <div class="col-sm-8 text-muted">{{ $student->first_name }} {{ $student->middle_name }} {{ $student->last_name }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Gender</div>
                        <div class="col-sm-8 text-muted">{{ $student->gender }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Age</div>
                        <div class="col-sm-8 text-muted">{{ \Carbon\Carbon::parse($student->dob)->age }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Date of Birth</div>
                        <div class="col-sm-8 text-muted">{{ $student->dob }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">LRN</div>
                        <div class="col-sm-8 text-muted">{{ $student->lrn }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-4 fw-semibold">Student ID</div>
                        <div class="col-sm-8 text-muted">{{ $student->student_id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Guardian Info -->
    @if ($student->guardian)
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">👨‍👩‍👧 Guardian Information</h5>

                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Full Name</div>
                        <div class="col-sm-9 text-muted">{{ $student->guardian->first_name }} {{ $student->guardian->middle_initial }} {{ $student->guardian->last_name }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Phone No.</div>
                        <div class="col-sm-9 text-muted">{{ $student->guardian->phone_number }}</div>
                    </div>
                    <hr>

                    <div class="row mb-3">
                        <div class="col-sm-3 fw-semibold">Address</div>
                        <div class="col-sm-9 text-muted">{{ $student->guardian->address }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.title = "Student Profile | Acadtrack";
</script>
@endsection
