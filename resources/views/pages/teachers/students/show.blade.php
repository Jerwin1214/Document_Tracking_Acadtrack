@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-5">

    {{-- Page Header --}}
    <div class="text-center mb-5">
        <h2 class="fw-bold">👩‍🎓 {{ $student->first_name }} {{ $student->last_name }}'s Profile</h2>
        <p class="text-muted">Overview of the student and guardian details</p>
    </div>

    <div class="row g-4">
        {{-- Profile Card --}}
        <div class="col-lg-4 col-md-5">
            <div class="card shadow-lg border-0 text-center p-4 rounded-4 glass-card">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                     alt="avatar" class="rounded-circle img-fluid mb-3" style="width: 150px;">
                <h5 class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</h5>
                <span class="badge bg-primary mt-2">{{ $student->gender }}</span>
            </div>
        </div>

        {{-- Student Details --}}
        <div class="col-lg-8 col-md-7">
            <div class="card shadow-lg border-0 p-4 rounded-4 glass-card">
                <h5 class="fw-bold mb-3"><i class="fas fa-user-graduate me-2"></i>Student Details</h5>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-id-card me-1"></i>Full Name</div>
                    <div class="col-sm-8 text-muted">{{ $student->first_name }} {{ $student->middle_name ?? '' }} {{ $student->last_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-venus-mars me-1"></i>Gender</div>
                    <div class="col-sm-8 text-muted">{{ $student->gender }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-birthday-cake me-1"></i>Age</div>
                    <div class="col-sm-8 text-muted">{{ \Carbon\Carbon::parse($student->dob)->age }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-calendar-alt me-1"></i>DOB</div>
                    <div class="col-sm-8 text-muted">{{ \Carbon\Carbon::parse($student->dob)->format('F j, Y') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-key me-1"></i>LRN</div>
                    <div class="col-sm-8 text-muted">{{ $student->lrn }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-id-badge me-1"></i>Student ID</div>
                    <div class="col-sm-8 text-muted">{{ $student->student_id }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Guardian Details --}}
    @if ($student->guardian)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 p-4 rounded-4 glass-card">
                <h5 class="fw-bold mb-3"><i class="fas fa-user-tie me-2"></i>Guardian Details</h5>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-id-card me-1"></i>Full Name</div>
                    <div class="col-sm-8 text-muted">{{ $student->guardian->first_name }} {{ $student->guardian->middle_initial ?? '' }} {{ $student->guardian->last_name }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-phone-alt me-1"></i>Phone No.</div>
                    <div class="col-sm-8 text-muted">{{ $student->guardian->phone_number }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 fw-bold"><i class="fas fa-home me-1"></i>Address</div>
                    <div class="col-sm-8 text-muted">{{ $student->guardian->address }}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<script>
    document.title = "Student Profile | Student Management System";
</script>

<style>
    /* Glass Card Effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(12px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }


    /* Responsive Adjustments */
    @media (max-width: 767px) {
        .card img {
            width: 120px;
        }
        .fw-bold {
            font-size: 0.95rem;
        }
        .text-muted {
            font-size: 0.9rem;
        }
        h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection
