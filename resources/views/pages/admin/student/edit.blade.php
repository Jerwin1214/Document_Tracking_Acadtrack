@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    <!-- Page Title -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">✏️ Update Student: {{ $student->first_name }} {{ $student->last_name }}</h2>

        <!-- Back Button -->
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary">
            ⬅ Back to Student List
        </a>
    </div>

    <form action="{{ route('admin.students.update', $student->id) }}" method="POST" class="card shadow-sm border-0 p-4">
        @csrf
        @method('PATCH')

      <!-- Student Info -->
<h5 class="fw-bold text-primary mb-3">📋 Student Information</h5>
<div class="row">

    <div class="col-md-4">
    <div class="mb-3">
        <label for="lrn" class="form-label">LRN</label>
        <input type="text" class="form-control @error('lrn') is-invalid @enderror"
               id="lrn" name="lrn"
               maxlength="12"
               pattern="\d{12}"
               inputmode="numeric"
               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);"
               placeholder="Enter 12-digit LRN"
               value="{{ old('lrn', $student->lrn) }}" required>
        @error('lrn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>


    <div class="col-md-4">
        <div class="mb-3">
            <label for="fname" class="form-label">First Name</label>
            <input type="text" class="form-control @error('std_first_name') is-invalid @enderror"
                   id="fname" name="std_first_name"
                   value="{{ old('std_first_name', $student->first_name) }}" required>
            @error('std_first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="mname" class="form-label">Middle Name</label>
            <input type="text" class="form-control @error('std_middle_name') is-invalid @enderror"
                   id="mname" name="std_middle_name"
                   value="{{ old('std_middle_name', $student->middle_name) }}">
            @error('std_middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="lname" class="form-label">Last Name</label>
            <input type="text" class="form-control @error('std_last_name') is-invalid @enderror"
                   id="lname" name="std_last_name"
                   value="{{ old('std_last_name', $student->last_name) }}" required>
            @error('std_last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="gender" class="form-label">Gender</label>
            <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                <option value="">-- Choose One --</option>
                <option value="Male" {{ old('gender', $student->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $student->gender) === 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" class="form-control @error('dob') is-invalid @enderror"
                   id="dob" name="dob"
                   value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '') }}"
                   required>
            @error('dob') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="col-md-4">
        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control @error('address') is-invalid @enderror"
                   id="address" name="address"
                   value="{{ old('address', $student->address) }}" required>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

        <hr class="my-4">

        <!-- Guardian Info -->
        <h5 class="fw-bold text-primary mb-3">👨‍👩‍👧 Guardian Information</h5>
        @if ($student->guardian)
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="initials" class="form-label">Middle Initial</label>
                    <input type="text" class="form-control @error('g_middle_initial') is-invalid @enderror"
                           id="initials" name="g_middle_initial"
                           value="{{ old('g_middle_initial', $student->guardian->middle_initial) }}">
                    @error('g_middle_initial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="g_fname" class="form-label">First Name</label>
                    <input type="text" class="form-control @error('g_first_name') is-invalid @enderror"
                           id="g_fname" name="g_first_name"
                           value="{{ old('g_first_name', $student->guardian->first_name) }}" required>
                    @error('g_first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="g_lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control @error('g_last_name') is-invalid @enderror"
                           id="g_lname" name="g_last_name"
                           value="{{ old('g_last_name', $student->guardian->last_name) }}" required>
                    @error('g_last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="g_address" class="form-label">Address</label>
                    <input type="text" class="form-control @error('g_address') is-invalid @enderror"
                           id="g_address" name="g_address"
                           value="{{ old('g_address', $student->guardian->address) }}" required>
                    @error('g_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label for="g_phone" class="form-label">Phone No</label>
                    <input type="text"
                           class="form-control"
                           id="g_phone" name="g_phone"
                           maxlength="11"
                           pattern="0\d{10}"
                           inputmode="numeric"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);"
                           placeholder="09976142271"
                           value="{{ old('g_phone', $student->guardian->phone_number ?? '') }}" required>
                    <x-form-error name="g_phone"/>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning shadow-sm">⚠️ No guardian data found for this student.</div>
        @endif

        <div class="mt-4 d-flex justify-content-end">
            <button type="reset" class="btn btn-secondary me-2">Clear</button>
            <button type="submit" class="btn btn-warning">💾 Update Student</button>
        </div>


    </form>
</div>

<!-- SweetAlert if update success -->
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Updated!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6'
    });
</script>
@endif
@if (session('warning'))
<script>
    Swal.fire({
        icon: 'warning',
        title: 'Missing Guardian',
        text: '{{ session("warning") }}',
        confirmButtonColor: '#d33'
    });
</script>
@endif

<script>
    document.title = "Edit Student | Student Management System";
</script>
@endsection
