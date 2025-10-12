@extends('pages.admin.admin-content')

@section('content')
<div class="d-flex align-items-center mb-4">
    <h2 class="mb-0">
        <i class="fa-solid fa-user-plus text-primary me-2"></i> Add Student
    </h2>

    <div class="ms-auto d-flex align-items-center gap-2">
        {{-- 🔙 Back Button --}}
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back
        </a>

        {{-- 📤 Bulk Upload --}}
        <form action="/admin/students/upload" method="post" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" onclick="document.getElementById('fileInput').click()">
                <i class="fa-solid fa-file-excel me-1"></i> Bulk Upload
            </button>
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" style="display: none;" onchange="submitForm()"/>
            <x-form-error name="file"/>
        </form>
    </div>
</div>

<form action="/admin/students" method="post" class="row g-4">
    @csrf

    <!-- Student Info Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-primary text-white fw-bold">
                <i class="fa-solid fa-id-card me-2"></i> Student Info
            </div>
            <div class="card-body row g-3">

                <!-- Student ID -->
                <div class="col-md-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id"
                           value="{{ $nextStudentId ?? '' }}" readonly>
                    <small class="text-muted">Generated automatically</small>
                </div>

                <!-- LRN -->
                <div class="col-md-3">
                    <label for="lrn" class="form-label">LRN</label>
                    <input type="text" class="form-control" id="lrn" name="lrn"
                           value="{{ old('lrn') }}" required
                           maxlength="12" pattern="\d{12}"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <x-form-error name="lrn"/>
                </div>

                <!-- First Name -->
                <div class="col-md-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control text-capitalize" name="std_first_name" value="{{ old('std_first_name') }}" required>
                    <x-form-error name="std_first_name"/>
                </div>

                <!-- Middle Name -->
                <div class="col-md-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control text-capitalize" name="std_middle_name" value="{{ old('std_middle_name') }}">
                    <x-form-error name="std_middle_name"/>
                </div>

                <!-- Last Name -->
                <div class="col-md-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control text-capitalize" name="std_last_name" value="{{ old('std_last_name') }}" required>
                    <x-form-error name="std_last_name"/>
                </div>

                <!-- Gender -->
                <div class="col-md-3">
                    <label class="form-label">Sex</label>
                    <select name="gender" class="form-select" required>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- DOB -->
                <div class="col-md-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                    <x-form-error name="dob"/>
                </div>

                <!-- Age -->
                <div class="col-md-3">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" value="{{ old('age') }}" readonly>
                </div>

                <!-- Address -->
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" value="{{ old('address') }}" required>
                    <x-form-error name="address"/>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" required>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                    <x-form-error name="password"/>
                </div>
            </div>
        </div>
    </div>

    <!-- Guardian Info Card -->
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-success text-white fw-bold">
                <i class="fa-solid fa-user-shield me-2"></i> Guardian Info
            </div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control text-capitalize" name="g_first_name" value="{{ old('g_first_name') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Middle Initial</label>
                    <input type="text" class="form-control text-uppercase" name="g_middle_initial"
                           value="{{ old('g_middle_initial') }}" maxlength="1"
                           oninput="this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '').slice(0, 1);">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control text-capitalize" name="g_last_name" value="{{ old('g_last_name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" name="g_address" value="{{ old('g_address') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone No</label>
                    <input type="text" class="form-control" name="g_phone"
                           inputmode="numeric" pattern="^0[0-9]{10}$"
                           maxlength="11"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                           placeholder="09976142171" value="{{ old('g_phone') }}" required>
                    <small class="text-muted">Format: 0XXXXXXXXXX (11 digits)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="col-12 d-flex justify-content-end gap-2">
        <button type="reset" class="btn btn-light border">Clear</button>
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-plus me-1"></i> Add Student
        </button>
    </div>
</form>

{{-- Scripts --}}
<script>
    function submitForm() {
        const fileInput = document.getElementById('fileInput');
        if (fileInput.files.length > 0) {
            document.getElementById('uploadForm').submit();
        }
    }

    // Toggle password
    document.getElementById("togglePassword").addEventListener("click", function () {
        const passwordField = document.getElementById("password");
        const icon = this.querySelector("i");
        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
        } else {
            passwordField.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
        }
    });

    // Auto calculate age
    document.getElementById("dob").addEventListener("change", function () {
        const dob = new Date(this.value);
        if (!isNaN(dob)) {
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) age--;
            document.getElementById("age").value = age >= 0 ? age : "";
        }
    });
</script>

@if (session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true,
    });
</script>
@endif
@endsection
