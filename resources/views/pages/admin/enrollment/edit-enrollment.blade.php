@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fa-solid fa-file-pen me-2"></i> Edit Student Info
        </h2>
    </div>

    {{-- FORM --}}
    <form id="enrollmentForm" action="{{ route('admin.enrollment.update', $enrollment->id) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- SCHOOL INFO --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">School Year</label>
                    <input type="text" name="school_year" class="form-control" placeholder="2025-2026"
                           value="{{ old('school_year', $enrollment->school_year) }}" required>
                </div>
            </div>
        </div>

        {{-- LEARNER INFO --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Learner Information</div>
            <div class="card-body row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Full Name</label>
                    <div class="input-group">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                               value="{{ old('last_name', $enrollment->last_name) }}" required>
                        <input type="text" name="first_name" class="form-control" placeholder="First Name"
                               value="{{ old('first_name', $enrollment->first_name) }}" required>
                        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name"
                               value="{{ old('middle_name', $enrollment->middle_name) }}">
                        <input type="text" name="extension_name" class="form-control" placeholder="Extension"
                               value="{{ old('extension_name', $enrollment->extension_name) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">PSA Birth Certificate No.</label>
                    <input type="text" class="form-control" name="psa_birth_cert_no"
                           value="{{ old('psa_birth_cert_no', $enrollment->psa_birth_cert_no) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Learner Reference Number (LRN)</label>
                    <input type="text" class="form-control" name="lrn" maxlength="12"
                           value="{{ old('lrn', $enrollment->lrn) }}" placeholder="Enter 12-digit LRN">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date" class="form-control" name="birthdate"
                           value="{{ old('birthdate', $enrollment->birthdate ? \Carbon\Carbon::parse($enrollment->birthdate)->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Place of Birth</label>
                    <input type="text" class="form-control" name="place_of_birth"
                           value="{{ old('place_of_birth', $enrollment->place_of_birth) }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-select">
                        <option value="Male" {{ old('sex', $enrollment->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $enrollment->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age"
                           value="{{ old('age', $enrollment->age) }}" @readonly(true)>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Mother Tongue</label>
                    <input type="text" class="form-control" name="mother_tongue"
                           value="{{ old('mother_tongue', $enrollment->mother_tongue) }}">
                </div>
            </div>
        </div>

        {{-- ADDITIONAL LEARNER DETAILS --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Additional Learner Details</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label">Belonging to Indigenous Peoples?</label>
                    <input type="text" name="ip_specify" class="form-control"
                           placeholder="If yes, specify" value="{{ old('ip_specify', $enrollment->ip_specify) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">4Ps Beneficiary?</label>
                    <input type="text" name="household_id_no" class="form-control"
                           placeholder="If yes, enter ID number" value="{{ old('household_id_no', $enrollment->household_id_no) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Learner with Disability?</label>
                    <input type="text" name="disability_type" class="form-control"
                           placeholder="If yes, specify type" value="{{ old('disability_type', $enrollment->disability_type) }}">
                </div>
            </div>
        </div>

        {{-- ADDRESS --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Address Information</div>
            <div class="card-body row g-3">
                {{-- Current Address --}}
                <div class="col-md-12"><h6 class="fw-bold text-dark">Current Address</h6></div>
                <div class="col-md-3"><input type="text" name="current_house_no" class="form-control" placeholder="House No." value="{{ old('current_house_no', $enrollment->current_house_no) }}"></div>
                <div class="col-md-3"><input type="text" name="current_street" class="form-control" placeholder="Street" value="{{ old('current_street', $enrollment->current_street) }}"></div>
                <div class="col-md-3"><input type="text" name="current_barangay" class="form-control" placeholder="Barangay" value="{{ old('current_barangay', $enrollment->current_barangay) }}"></div>
                <div class="col-md-3"><input type="text" name="current_city" class="form-control" placeholder="Municipality/City" value="{{ old('current_city', $enrollment->current_city) }}"></div>
                <div class="col-md-3"><input type="text" name="current_province" class="form-control" placeholder="Province" value="{{ old('current_province', $enrollment->current_province) }}"></div>
                <div class="col-md-3"><input type="text" name="current_country" class="form-control" placeholder="Country" value="{{ old('current_country', $enrollment->current_country) }}"></div>
                <div class="col-md-3"><input type="text" name="current_zip" class="form-control" placeholder="Zip Code" value="{{ old('current_zip', $enrollment->current_zip) }}"></div>

                {{-- Permanent Address --}}
                <div class="col-md-12 mt-3"><h6 class="fw-bold text-dark">Permanent Address</h6></div>
                <div class="col-md-12 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sameAddress" name="same_address" value="1" {{ old('same_address', $enrollment->same_address) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="sameAddress">Same as Current Address</label>
                    </div>
                </div>
                <div class="col-md-3"><input type="text" name="permanent_house_no" class="form-control" placeholder="House No." value="{{ old('permanent_house_no', $enrollment->permanent_house_no) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_street" class="form-control" placeholder="Street" value="{{ old('permanent_street', $enrollment->permanent_street) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_barangay" class="form-control" placeholder="Barangay" value="{{ old('permanent_barangay', $enrollment->permanent_barangay) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_city" class="form-control" placeholder="Municipality/City" value="{{ old('permanent_city', $enrollment->permanent_city) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_province" class="form-control" placeholder="Province" value="{{ old('permanent_province', $enrollment->permanent_province) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_country" class="form-control" placeholder="Country" value="{{ old('permanent_country', $enrollment->permanent_country) }}"></div>
                <div class="col-md-3"><input type="text" name="permanent_zip" class="form-control" placeholder="Zip Code" value="{{ old('permanent_zip', $enrollment->permanent_zip) }}"></div>
            </div>
        </div>

        {{-- PARENT/GUARDIAN --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Parent / Guardian Information</div>
            <div class="card-body row g-3">
                {{-- Father --}}
                <div class="col-md-6">
                    <label class="form-label">Father’s Name</label>
                    <div class="input-group">
                        <input type="text" name="father_first_name" class="form-control" placeholder="First Name" value="{{ old('father_first_name', $enrollment->father_first_name) }}">
                        <input type="text" name="father_middle_name" class="form-control" placeholder="Middle Name" value="{{ old('father_middle_name', $enrollment->father_middle_name) }}">
                        <input type="text" name="father_last_name" class="form-control" placeholder="Last Name" value="{{ old('father_last_name', $enrollment->father_last_name) }}">
                    </div>
                    <input type="text" name="father_contact" class="form-control mt-2" placeholder="Contact Number" value="{{ old('father_contact', $enrollment->father_contact) }}">
                </div>

                {{-- Mother --}}
                <div class="col-md-6">
                    <label class="form-label">Mother’s Maiden Name</label>
                    <div class="input-group">
                        <input type="text" name="mother_first_name" class="form-control" placeholder="First Name" value="{{ old('mother_first_name', $enrollment->mother_first_name) }}">
                        <input type="text" name="mother_middle_name" class="form-control" placeholder="Middle Name" value="{{ old('mother_middle_name', $enrollment->mother_middle_name) }}">
                        <input type="text" name="mother_last_name" class="form-control" placeholder="Last Name" value="{{ old('mother_last_name', $enrollment->mother_last_name) }}">
                    </div>
                    <input type="text" name="mother_contact" class="form-control mt-2" placeholder="Contact Number" value="{{ old('mother_contact', $enrollment->mother_contact) }}">
                </div>

                {{-- Guardian --}}
                <div class="col-md-6">
                    <label class="form-label">Legal Guardian</label>
                    <div class="input-group">
                        <input type="text" name="guardian_first_name" class="form-control" placeholder="First Name" value="{{ old('guardian_first_name', $enrollment->guardian_first_name) }}">
                        <input type="text" name="guardian_middle_name" class="form-control" placeholder="Middle Name" value="{{ old('guardian_middle_name', $enrollment->guardian_middle_name) }}">
                        <input type="text" name="guardian_last_name" class="form-control" placeholder="Last Name" value="{{ old('guardian_last_name', $enrollment->guardian_last_name) }}">
                    </div>
                    <input type="text" name="guardian_contact" class="form-control mt-2" placeholder="Contact Number" value="{{ old('guardian_contact', $enrollment->guardian_contact) }}">
                </div>
            </div>
        </div>

        {{-- SUBMIT + BACK --}}
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('admin.enrollment.index') }}" class="btn btn-outline-secondary align-self-center">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div class="ms-auto d-flex gap-2">
                <button type="reset" class="btn btn-light border">Clear</button>
                <button type="submit" class="btn btn-dark">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Update
                </button>
            </div>
        </div>
    </form>
</div>

{{-- SWEETALERT & VALIDATION SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');

    form.addEventListener('submit', function(e) {
        let errors = [];
        const requiredFields = ['school_year', 'last_name', 'first_name', 'lrn'];
        requiredFields.forEach(name => {
            const val = form.querySelector(`[name="${name}"]`).value.trim();
            if (!val) errors.push(`${name.replace('_',' ')} is required.`);
        });

        const lrn = form.querySelector('[name="lrn"]').value.trim();
        if (lrn && !/^\d{12}$/.test(lrn)) errors.push('LRN must be exactly 12 digits.');

        const contactFields = ['father_contact', 'mother_contact', 'guardian_contact'];
        contactFields.forEach(field => {
            const val = form.querySelector(`[name="${field}"]`).value.trim();
            if (val && !/^\d{11}$/.test(val)) errors.push(`${field.replace('_',' ')} must be 11 digits.`);
        });

        if (errors.length > 0) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: errors.join('<br>'),
                confirmButtonColor: '#3085d6'
            });
        }
    });

    // Same as Current Address
    const sameAddressCheckbox = document.getElementById('sameAddress');
    const currentFields = {
        house_no: document.querySelector('[name="current_house_no"]'),
        street: document.querySelector('[name="current_street"]'),
        barangay: document.querySelector('[name="current_barangay"]'),
        city: document.querySelector('[name="current_city"]'),
        province: document.querySelector('[name="current_province"]'),
        country: document.querySelector('[name="current_country"]'),
        zip: document.querySelector('[name="current_zip"]')
    };
    const permanentFields = {
        house_no: document.querySelector('[name="permanent_house_no"]'),
        street: document.querySelector('[name="permanent_street"]'),
        barangay: document.querySelector('[name="permanent_barangay"]'),
        city: document.querySelector('[name="permanent_city"]'),
        province: document.querySelector('[name="permanent_province"]'),
        country: document.querySelector('[name="permanent_country"]'),
        zip: document.querySelector('[name="permanent_zip"]')
    };

    function copyAddress() {
        if (sameAddressCheckbox.checked) {
            for (let key in currentFields) permanentFields[key].value = currentFields[key].value;
        }
    }

    copyAddress();
    sameAddressCheckbox.addEventListener('change', copyAddress);
    for (let key in currentFields) {
        currentFields[key].addEventListener('input', function() {
            if (sameAddressCheckbox.checked) permanentFields[key].value = this.value;
        });
    }
});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: '{{ session('success') }}',
    confirmButtonColor: '#3085d6'
});
</script>
@endif
@endsection
