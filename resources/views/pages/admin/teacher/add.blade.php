@extends('pages.admin.admin-content')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-success fw-bold">
        <i class="fas fa-user-plus me-2"></i>Add New Teacher
    </h2>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <form action="/admin/teachers" method="post">
                @csrf
                <div class="row">
                    {{-- Salutation --}}
                    <div class="col-md-2 mb-3">
                        <label for="salutation" class="form-label fw-semibold">Salutation</label>
                        <select name="salutation" id="salutation" class="form-select" required>
                            <option value="">-- Choose One --</option>
                            <option value="Dr.">Dr.</option>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Miss.">Miss.</option>
                        </select>
                        <x-form-error name="salutation" />
                    </div>

                    {{-- First Name --}}
                    <div class="col-md-3 mb-3">
                        <label for="first_name" class="form-label fw-semibold">First Name</label>
                        <input type="text" class="form-control" id="first_name"
                               name="first_name" value="{{ old('first_name') }}" required>
                        <x-form-error name="first_name" />
                    </div>

                    {{-- Middle Name --}}
                    <div class="col-md-3 mb-3">
                        <label for="middle_name" class="form-label fw-semibold">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name"
                               name="middle_name" value="{{ old('middle_name') }}">
                        <x-form-error name="middle_name" />
                    </div>

                    {{-- Last Name --}}
                    <div class="col-md-4 mb-3">
                        <label for="last_name" class="form-label fw-semibold">Last Name</label>
                        <input type="text" class="form-control" id="last_name"
                               name="last_name" value="{{ old('last_name') }}" required>
                        <x-form-error name="last_name" />
                    </div>

                    {{-- Gender --}}
                    <div class="col-md-3 mb-3">
                        <label for="gender" class="form-label fw-semibold">Gender</label>
                        <select name="gender" id="gender" class="form-select" required>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                        <x-form-error name="gender" />
                    </div>

                    {{-- DOB --}}
                    <div class="col-md-3 mb-3">
                        <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" class="form-control" id="dob"
                               name="dob" value="{{ old('dob') }}" required>
                        <x-form-error name="dob" />
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <input type="text" class="form-control" id="address"
                               name="address" value="{{ old('address') }}" required>
                        <x-form-error name="address" />
                    </div>

                    {{-- User ID --}}
                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label fw-semibold">User ID</label>
                        <input type="text" class="form-control" id="user_id"
                               name="user_id" value="{{ old('user_id') }}" required>
                        <x-form-error name="user_id" />
                    </div>

                    {{-- Password --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password"
                                   name="password" required>
                            <span class="input-group-text">
                                <i class="fa fa-eye" id="togglePassword" style="cursor:pointer;"></i>
                            </span>
                        </div>
                        <x-form-error name="password"/>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-plus me-2"></i>Add Teacher
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2 px-4">
                        <i class="fas fa-undo me-2"></i>Clear
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.title = 'Add New Teacher | Student Management System';

    // Password toggle
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
        const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
        passwordField.setAttribute("type", type);
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
});
</script>
@endsection
