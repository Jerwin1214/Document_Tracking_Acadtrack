@extends('pages.admin.admin-content')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-primary fw-bold">
        <i class="fas fa-user-edit me-2"></i>Edit Teacher Information
    </h2>

    <div class="card shadow-lg rounded-3 border-0">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.teachers.update', $teacher->id) }}">
                @csrf
                @method('PATCH')

                {{-- Row 1 --}}
                <div class="row mb-3">
                    <div class="col-md-2">
                        <label for="salutation" class="form-label fw-semibold">Salutation</label>
                        <select name="salutation" class="form-select" required>
                            <option value="Mr." {{ $teacher->salutation == 'Mr.' ? 'selected' : '' }}>Mr.</option>
                            <option value="Ms." {{ $teacher->salutation == 'Ms.' ? 'selected' : '' }}>Ms.</option>
                            <option value="Dr." {{ $teacher->salutation == 'Dr.' ? 'selected' : '' }}>Dr.</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="first_name" class="form-label fw-semibold">First Name</label>
                        <input type="text" name="first_name" class="form-control"
                               value="{{ $teacher->first_name }}" required>
                    </div>

                    <div class="col-md-3">
                        <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                        <input type="text" name="middle_name" class="form-control"
                               value="{{ $teacher->middle_name }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="last_name" class="form-label fw-semibold">Last Name</label>
                        <input type="text" name="last_name" class="form-control"
                               value="{{ $teacher->last_name }}" required>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="gender" class="form-label fw-semibold">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="Male" {{ $teacher->gender == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ $teacher->gender == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control"
                               value="{{ $teacher->dob ? \Carbon\Carbon::parse($teacher->dob)->format('Y-m-d') : '' }}" required>
                    </div>

                    <div class="col-md-4">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control"
                               value="{{ $teacher->address }}" required>
                    </div>
                </div>

                {{-- Row 3: Editable User ID from users table --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="user_id" class="form-label fw-semibold">Login User ID</label>
                        <input type="text" name="user_id" class="form-control"
                               value="{{ $teacher->user->user_id ?? '' }}" required>
                        <small class="text-muted">This is the ID used for login. Must be unique.</small>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Update
                    </button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="fas fa-times me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert for success --}}
@if(session('success'))
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: "{{ session('success') }}",
        timer: 2500,
        showConfirmButton: false
    });
</script>
@endif

<script>
    document.title = 'Edit Teacher | Student Management System';
</script>
@endsection
