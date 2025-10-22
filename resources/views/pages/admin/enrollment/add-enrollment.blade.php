@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-dark">
            <i class="fa-solid fa-file-pen me-2"></i> {{ isset($enrollment) ? 'Edit Enrollment' : 'Student Form' }}
        </h2>
        <a href="{{ route('admin.enrollment.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

    {{-- FORM --}}
    <form action="{{ isset($enrollment) ? route('admin.enrollment.update', $enrollment->id) : route('admin.enrollment.store') }}"
          method="POST" id="enrollmentForm">
        @csrf
        @if(isset($enrollment))
            @method('PATCH')
        @endif

        {{-- SCHOOL INFO --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-body row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold">School Year</label>
                    <input type="text" name="school_year" class="form-control" placeholder="2025-2026"
                           value="{{ old('school_year', $enrollment->school_year ?? '') }}" required>
                </div>
            </div>
        </div>
        <div class="col-md-3">
    <label class="form-label fw-bold">Grade Level to Enroll</label>
    <select name="grade_level" class="form-select" required>
        <option value="" disabled selected>Select Grade Level</option>
        <option value="Kindergarten" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
        <option value="Grade 1" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
        <option value="Grade 2" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
        <option value="Grade 3" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
        <option value="Grade 4" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
        <option value="Grade 5" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
        <option value="Grade 6" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
        <option value="Grade 7" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
        <option value="Grade 8" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
        <option value="Grade 9" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
        <option value="Grade 10" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
        <option value="Grade 11" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
        <option value="Grade 12" {{ old('grade_level', $enrollment->grade_level ?? '') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
    </select>
</div>


        {{-- LEARNER INFO --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Learner Information</div>
            <div class="card-body row g-3">
                <div class="col-md-12">
                    <label class="form-label fw-bold">Full Name</label>
                    <div class="input-group">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                               value="{{ old('last_name', $enrollment->last_name ?? '') }}" required>
                        <input type="text" name="first_name" class="form-control" placeholder="First Name"
                               value="{{ old('first_name', $enrollment->first_name ?? '') }}" required>
                        <input type="text" name="middle_name" class="form-control" placeholder="Middle Name"
                               value="{{ old('middle_name', $enrollment->middle_name ?? '') }}">
                        <input type="text" name="extension_name" class="form-control" placeholder="Extension"
                               value="{{ old('extension_name', $enrollment->extension_name ?? '') }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">PSA Birth Certificate No.</label>
                    <input type="text" class="form-control" name="psa_birth_cert_no"
                           value="{{ old('psa_birth_cert_no', $enrollment->psa_birth_cert_no ?? '') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Learner Reference Number (LRN)</label>
                    <input type="text" class="form-control" name="lrn" maxlength="12"
                           value="{{ old('lrn', $enrollment->lrn ?? '') }}" placeholder="Enter 12-digit LRN">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Birthdate</label>
                    <input type="date" class="form-control" name="birthdate" id="birthdate"
                           value="{{ old('birthdate', isset($enrollment->birthdate) ? \Carbon\Carbon::parse($enrollment->birthdate)->format('Y-m-d') : '') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Age</label>
                    <input type="number" class="form-control" name="age" id="age" readonly
                           value="{{ old('age', $enrollment->age ?? '') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Place of Birth</label>
                    <input type="text" class="form-control" name="place_of_birth"
                           value="{{ old('place_of_birth', $enrollment->place_of_birth ?? '') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-select">
                        <option value="Male" {{ old('sex', $enrollment->sex ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('sex', $enrollment->sex ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Mother Tongue</label>
                    <input type="text" class="form-control" name="mother_tongue"
                           value="{{ old('mother_tongue', $enrollment->mother_tongue ?? '') }}">
                </div>
            </div>
        </div>

  {{-- ADDITIONAL INFO --}}
<div class="card border-0 shadow-sm rounded-3 mb-3">
    <div class="card-header bg-light fw-bold">Additional Learner Details</div>
    <div class="card-body row g-3">
        <div class="col-md-4">
            <label class="form-label">Indigenous People</label>
            <input type="text" name="indigenous_people" class="form-control"
                   value="{{ old('indigenous_people', $enrollment->indigenous_people ?? '') }}"
                   placeholder="Specify if applicable">
        </div>

        <div class="col-md-4">
            <label class="form-label">4Ps Beneficiary</label>
            <input type="text" name="fourps_beneficiary" class="form-control"
                   value="{{ old('fourps_beneficiary', $enrollment->fourps_beneficiary ?? '') }}"
                   placeholder="Specify if applicable">
        </div>

        <div class="col-md-4">
            <label class="form-label">Learner with Disability</label>
            <input type="text" name="learner_with_disability" class="form-control"
                   value="{{ old('learner_with_disability', $enrollment->learner_with_disability ?? '') }}"
                   placeholder="Specify type if applicable">
        </div>
    </div>
</div>


        {{-- ADDRESS --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Address Information</div>
            <div class="card-body row g-3">
                <div class="col-md-12"><h6 class="fw-bold text-dark">Current Address</h6></div>
                <div class="col-md-3"><input type="text" name="current_house_no" class="form-control" value="{{ old('current_house_no', $enrollment->current_house_no ?? '') }}" placeholder="House No."></div>
                <div class="col-md-3"><input type="text" name="current_street" class="form-control" value="{{ old('current_street', $enrollment->current_street ?? '') }}" placeholder="Street"></div>
                <div class="col-md-3"><input type="text" name="current_barangay" class="form-control" value="{{ old('current_barangay', $enrollment->current_barangay ?? '') }}" placeholder="Barangay"></div>
                <div class="col-md-3"><input type="text" name="current_city" class="form-control" value="{{ old('current_city', $enrollment->current_city ?? '') }}" placeholder="Municipality/City"></div>
                <div class="col-md-3"><input type="text" name="current_province" class="form-control" value="{{ old('current_province', $enrollment->current_province ?? '') }}" placeholder="Province"></div>
                <div class="col-md-3"><input type="text" name="current_country" class="form-control" value="{{ old('current_country', $enrollment->current_country ?? '') }}" placeholder="Country"></div>
                <div class="col-md-3"><input type="text" name="current_zip" class="form-control" value="{{ old('current_zip', $enrollment->current_zip ?? '') }}" placeholder="Zip Code"></div>

                <div class="col-md-12 mt-3">
                    <h6 class="fw-bold text-dark">Permanent Address</h6>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="sameAddress" name="same_address" value="1" {{ old('same_address', $enrollment->same_address ?? 1) ? 'checked' : '' }}>
                        <label class="form-check-label fw-bold" for="sameAddress">Same as Current Address</label>
                    </div>
                </div>

                <div class="col-md-3"><input type="text" name="permanent_house_no" class="form-control" value="{{ old('permanent_house_no', $enrollment->permanent_house_no ?? '') }}" placeholder="House No."></div>
                <div class="col-md-3"><input type="text" name="permanent_street" class="form-control" value="{{ old('permanent_street', $enrollment->permanent_street ?? '') }}" placeholder="Street"></div>
                <div class="col-md-3"><input type="text" name="permanent_barangay" class="form-control" value="{{ old('permanent_barangay', $enrollment->permanent_barangay ?? '') }}" placeholder="Barangay"></div>
                <div class="col-md-3"><input type="text" name="permanent_city" class="form-control" value="{{ old('permanent_city', $enrollment->permanent_city ?? '') }}" placeholder="Municipality/City"></div>
                <div class="col-md-3"><input type="text" name="permanent_province" class="form-control" value="{{ old('permanent_province', $enrollment->permanent_province ?? '') }}" placeholder="Province"></div>
                <div class="col-md-3"><input type="text" name="permanent_country" class="form-control" value="{{ old('permanent_country', $enrollment->permanent_country ?? '') }}" placeholder="Country"></div>
                <div class="col-md-3"><input type="text" name="permanent_zip" class="form-control" value="{{ old('permanent_zip', $enrollment->permanent_zip ?? '') }}" placeholder="Zip Code"></div>
            </div>
        </div>

        {{-- PARENT/GUARDIAN --}}
        <div class="card border-0 shadow-sm rounded-3 mb-3">
            <div class="card-header bg-light fw-bold">Parent / Guardian Information</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label">Father’s Name</label>
                    <div class="input-group">
                        <input type="text" name="father_first_name" class="form-control" value="{{ old('father_first_name', $enrollment->father_first_name ?? '') }}" placeholder="First Name">
                        <input type="text" name="father_middle_name" class="form-control" value="{{ old('father_middle_name', $enrollment->father_middle_name ?? '') }}" placeholder="Middle Name">
                        <input type="text" name="father_last_name" class="form-control" value="{{ old('father_last_name', $enrollment->father_last_name ?? '') }}" placeholder="Last Name">
                    </div>
                    <input type="text" name="father_contact" class="form-control mt-2" value="{{ old('father_contact', $enrollment->father_contact ?? '') }}" placeholder="Contact Number" maxlength="11">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother’s Maiden Name</label>
                    <div class="input-group">
                        <input type="text" name="mother_first_name" class="form-control" value="{{ old('mother_first_name', $enrollment->mother_first_name ?? '') }}" placeholder="First Name">
                        <input type="text" name="mother_middle_name" class="form-control" value="{{ old('mother_middle_name', $enrollment->mother_middle_name ?? '') }}" placeholder="Middle Name">
                        <input type="text" name="mother_last_name" class="form-control" value="{{ old('mother_last_name', $enrollment->mother_last_name ?? '') }}" placeholder="Last Name">
                    </div>
                    <input type="text" name="mother_contact" class="form-control mt-2" value="{{ old('mother_contact', $enrollment->mother_contact ?? '') }}" placeholder="Contact Number" maxlength="11">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Legal Guardian</label>
                    <div class="input-group">
                        <input type="text" name="guardian_first_name" class="form-control" value="{{ old('guardian_first_name', $enrollment->guardian_first_name ?? '') }}" placeholder="First Name">
                        <input type="text" name="guardian_middle_name" class="form-control" value="{{ old('guardian_middle_name', $enrollment->guardian_middle_name ?? '') }}" placeholder="Middle Name">
                        <input type="text" name="guardian_last_name" class="form-control" value="{{ old('guardian_last_name', $enrollment->guardian_last_name ?? '') }}" placeholder="Last Name">
                    </div>
                    <input type="text" name="guardian_contact" class="form-control mt-2" value="{{ old('guardian_contact', $enrollment->guardian_contact ?? '') }}" placeholder="Contact Number" maxlength="11">
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="reset" class="btn btn-light border">Clear</button>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-floppy-disk me-1"></i> {{ isset($enrollment) ? 'Update Enrollment' : 'Save Enrollment' }}
            </button>
        </div>
    </form>
</div>

{{-- SWEETALERT, ADDRESS COPY, & AGE CALCULATOR SCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('enrollmentForm');

    // ✅ Contact number validation (11 digits only)
    form.addEventListener('submit', function(e) {
        const contactFields = ['father_contact', 'mother_contact', 'guardian_contact'];
        for (let field of contactFields) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input.value.trim() !== '' && !/^\d{11}$/.test(input.value.trim())) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Contact Number',
                    text: 'Contact numbers must be exactly 11 digits and numeric only.',
                    confirmButtonColor: '#3085d6'
                });
                input.focus();
                return false;
            }
        }
    });

    // Address Copy
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
            for (let key in currentFields) {
                permanentFields[key].value = currentFields[key].value;
            }
        }
    }

    copyAddress();
    sameAddressCheckbox.addEventListener('change', copyAddress);
    for (let key in currentFields) {
        currentFields[key].addEventListener('input', function() {
            if (sameAddressCheckbox.checked) {
                permanentFields[key].value = this.value;
            }
        });
    }

    // Auto-calculate age
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    function calculateAge() {
        const birthdateValue = birthdateInput.value;
        if (!birthdateValue) { ageInput.value = ''; return; }
        const birthDate = new Date(birthdateValue);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
        ageInput.value = age >= 0 ? age : '';
    }
    birthdateInput.addEventListener('change', calculateAge);
    calculateAge();
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
