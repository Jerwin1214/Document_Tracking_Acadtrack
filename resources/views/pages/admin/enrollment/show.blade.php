@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- MODERN RESPONSIVE HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 flex-wrap">
        {{-- Left Logo --}}
        <img src="{{ asset('images/depedlogo.jpg') }}"
             alt="DepEd Logo"
             class="logo-img">

        {{-- Center Title --}}
        <h4 class="fw-bold text-center flex-grow-1 mx-2 m-0 title-text">Student Information</h4>

        {{-- Right Logo --}}
        <img src="{{ asset('images/acadtracklogo.jpg') }}"
             alt="Acadtrack Logo"
             class="logo-img rounded-circle">
    </div>

    {{-- School Year & LRN --}}
    <div class="row mb-3">
        <div class="col-md-3">
            <label class="fw-bold">School Year</label>
            <div class="border p-2 rounded">{{ $enrollment->school_year }}</div>
        </div>
        <div class="col-md-3">
            <label class="fw-bold">Learner Reference No. (LRN)</label>
            <div class="border p-2 rounded">{{ $enrollment->lrn ?? '-' }}</div>
        </div>
    </div>

    {{-- Learner Information --}}
    <div class="border p-3 mb-3 rounded-3 shadow-sm">
        <h6 class="fw-bold border-bottom pb-2 mb-3">LEARNER INFORMATION</h6>
        <div class="row g-2">
            <div class="col-md-3">
                <label class="form-label">Last Name</label>
                <div class="border p-2 rounded">{{ $enrollment->last_name }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">First Name</label>
                <div class="border p-2 rounded">{{ $enrollment->first_name }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Middle Name</label>
                <div class="border p-2 rounded">{{ $enrollment->middle_name ?? '-' }}</div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Extension Name</label>
                <div class="border p-2 rounded">{{ $enrollment->extension_name ?? '-' }}</div>
            </div>

            <div class="col-md-3 mt-2">
                <label class="form-label">Birthdate</label>
                <div class="border p-2 rounded">{{ $enrollment->birthdate?->format('Y-m-d') ?? '-' }}</div>
            </div>
            <div class="col-md-3 mt-2">
                <label class="form-label">Place of Birth</label>
                <div class="border p-2 rounded">{{ $enrollment->place_of_birth ?? '-' }}</div>
            </div>
            <div class="col-md-2 mt-2">
                <label class="form-label">Sex</label>
                <div class="border p-2 rounded">{{ $enrollment->sex ?? '-' }}</div>
            </div>
            <div class="col-md-2 mt-2">
                <label class="form-label">Age</label>
                <div class="border p-2 rounded">{{ $enrollment->age ?? '-' }}</div>
            </div>
            <div class="col-md-2 mt-2">
                <label class="form-label">Mother Tongue</label>
                <div class="border p-2 rounded">{{ $enrollment->mother_tongue ?? '-' }}</div>
            </div>

            {{-- Indigenous Peoples --}}
            <div class="col-md-4 mt-2">
                <label class="form-label">Indigenous Peoples?</label>
                @if($enrollment->is_ip)
                    <div class="border p-2 rounded">Specify: {{ $enrollment->ip_specify ?? '-' }}</div>
                @else
                    <div class="border p-2 rounded">-</div>
                @endif
            </div>

            {{-- 4Ps Beneficiary --}}
            <div class="col-md-4 mt-2">
                <label class="form-label">4Ps Beneficiary?</label>
                @if($enrollment->is_4ps_beneficiary)
                    <div class="border p-2 rounded">ID No.: {{ $enrollment->household_id_no ?? '-' }}</div>
                @else
                    <div class="border p-2 rounded">-</div>
                @endif
            </div>

            {{-- Learner with Disability --}}
            <div class="col-md-4 mt-2">
                <label class="form-label">Learner with Disability?</label>
                @if($enrollment->is_pwd)
                    <div class="border p-2 rounded">Type: {{ $enrollment->disability_type ?? '-' }}</div>
                @else
                    <div class="border p-2 rounded">-</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Address Section --}}
    <div class="border p-3 mb-3 rounded-3 shadow-sm">
        <h6 class="fw-bold border-bottom pb-2 mb-3">CURRENT ADDRESS</h6>
        <div class="row g-2">
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->current_house_no ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->current_street ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->current_barangay ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->current_city ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->current_province ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->current_country ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->current_zip ?? '-' }}</div></div>
        </div>

        <h6 class="fw-bold border-bottom pb-2 mt-3 mb-3">PERMANENT ADDRESS</h6>
        <div class="row g-2">
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->permanent_house_no ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->permanent_street ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->permanent_barangay ?? '-' }}</div></div>
            <div class="col-md-3"><div class="border p-2 rounded">{{ $enrollment->permanent_city ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->permanent_province ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->permanent_country ?? '-' }}</div></div>
            <div class="col-md-3 mt-2"><div class="border p-2 rounded">{{ $enrollment->permanent_zip ?? '-' }}</div></div>
        </div>
    </div>

    {{-- Parents / Guardians Section --}}
    <div class="border p-3 mb-3 rounded-3 shadow-sm">
        <h6 class="fw-bold border-bottom pb-2 mb-3">PARENTS/GUARDIANS INFORMATION</h6>
        <div class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Father’s Name</label>
                <div class="border p-2 rounded">{{ $enrollment->father_first_name }} {{ $enrollment->father_middle_name }} {{ $enrollment->father_last_name }}</div>
                <small>Contact: {{ $enrollment->father_contact ?? '-' }}</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Mother’s Maiden Name</label>
                <div class="border p-2 rounded">{{ $enrollment->mother_first_name }} {{ $enrollment->mother_middle_name }} {{ $enrollment->mother_last_name }}</div>
                <small>Contact: {{ $enrollment->mother_contact ?? '-' }}</small>
            </div>
            <div class="col-md-4">
                <label class="form-label">Legal Guardian</label>
                <div class="border p-2 rounded">{{ $enrollment->guardian_first_name }} {{ $enrollment->guardian_middle_name }} {{ $enrollment->guardian_last_name }}</div>
                <small>Contact: {{ $enrollment->guardian_contact ?? '-' }}</small>
            </div>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="d-flex justify-content-start mt-3">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>

</div>

{{-- Additional CSS for modern look --}}
<style>
    .logo-img {
        height: 60px;
        width: 60px;
        object-fit: cover;
    }
    .title-text {
        font-size: 1.5rem;
        color: #222;
        white-space: nowrap;
    }
    .border {
        border-color: #ddd !important;
    }
    .rounded-3 {
        border-radius: 0.5rem !important;
    }
    .shadow-sm {
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    /* Responsive logos */
    @media (max-width: 767px) {
        .logo-img {
            height: 50px;
            width: 50px;
        }
        .title-text {
            font-size: 1.2rem;
        }
    }
</style>
@endsection
