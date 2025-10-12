@extends('pages.students.student-content')

@section('content')
<div class="container py-3">
    {{-- Profile Header --}}
<div class="bg-primary bg-gradient text-white rounded-4 p-3 mb-4 d-flex flex-column flex-md-row align-items-center gap-3">
    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
         alt="avatar" class="rounded-circle border border-white" style="width: 100px; height: 100px;">
    <div class="text-center text-md-start flex-grow-1">
        <h3 class="fw-bold mb-1">{{ $student->first_name ?? 'Student' }} {{ $student->last_name ?? '' }}</h3>
        {{-- Removed gender/age line --}}
        <a href="/student/settings" class="btn btn-light btn-sm mt-1">Forget Password</a>
    </div>
</div>


    {{-- Profile Sections --}}
    <div class="row g-3">
        {{-- Personal Info --}}
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-3 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-person-circle me-2"></i>Personal Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Full Name
                            <span>{{ $student->first_name.' '.$student->middle_name.' '.$student->last_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Gender
                            <span>{{ $student->gender ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Date of Birth
                            <span>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->format('F j, Y') : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Age
                            <span>{{ $student->dob ? \Carbon\Carbon::parse($student->dob)->age : 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-3 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-key-fill me-2"></i>Account Info</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            LRN
                            <span>{{ $student->lrn ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Student ID
                            <span>{{ $student->student_id ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Created At
                            <span>{{ $student->created_at ? $student->created_at->format('F j, Y, g:i A') : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Updated At
                            <span>{{ $student->updated_at ? $student->updated_at->format('F j, Y, g:i A') : 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Academic Info --}}
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-3 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-journal-bookmark-fill me-2"></i>Academic Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Department
                            <span>{{ $student->class->department ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Year Level
                            <span>{{ $student->class->year_level ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Section
                            <span>{{ $student->class->section ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Total Subjects
                            <span class="badge bg-primary rounded-pill">{{ $student->subjects ? $student->subjects->count() : '0' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Guardian Info --}}
        @if($student->guardian)
        <div class="col-12 col-md-6">
            <div class="card shadow-sm rounded-4 p-3 h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3"><i class="bi bi-people-fill me-2"></i>Guardian Details</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Full Name
                            <span>{{ $student->guardian->first_name.' '.$student->guardian->middle_initial.' '.$student->guardian->last_name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Phone
                            <span>{{ $student->guardian->phone_number ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Address
                            <span>{{ $student->guardian->address ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).prop('title', '{{ $student->first_name ?? "Student" }} Profile | Student Management System');
    });
</script>

<style>
/* Mobile first adjustments */
@media (max-width: 991px) {
    .card-body ul.list-group-item {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 767px) {
    .bg-primary h3 {
        font-size: 1.2rem;
    }
    .bg-primary p {
        font-size: 0.8rem;
    }
    .bg-primary img {
        width: 80px;
        height: 80px;
    }
}
</style>
@endsection
