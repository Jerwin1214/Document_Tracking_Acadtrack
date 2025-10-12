@extends('pages.admin.admin-content')

@section('content')
<div class="container">

    {{-- 🔙 Back Button --}}
    <div class="mt-3 mb-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-outline-primary">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Student List
        </a>
    </div>

    <h2>Assign Student to Class</h2>

    <form method="POST" action="{{ route('admin.students.assignDepartment') }}" class="shadow-lg p-4 mb-5 mt-3 bg-body-tertiary rounded">
        @csrf

        <div class="row g-3">
            {{-- Search Student --}}
            <div class="col-md-6 d-flex align-items-end">
                <div class="flex-grow-1 me-2">
                    <label for="student_search" class="form-label">Search Student (by Name or LRN)</label>
                    <input
                        list="studentList"
                        id="student_search"
                        class="form-control"
                        placeholder="Enter LRN or Name"
                        autocomplete="off"
                        required
                        value="{{ isset($student) ? $student?->lrn . ' - ' . $student?->first_name . ' ' . $student?->last_name : '' }}">

                    <input type="hidden" name="student_id" id="student_id" required value="{{ $student?->id ?? '' }}">

                    <datalist id="studentList">
                        @foreach($students as $s)
                            <option
                                data-id="{{ $s->id }}"
                                data-department="{{ $s->department ?? 'N/A' }}"
                                data-year="{{ $s->year_level ?? 'N/A' }}"
                                data-section="{{ $s->section ?? 'N/A' }}"
                                data-strand="{{ $s->strand ?? 'N/A' }}"
                                value="{{ $s->lrn }} - {{ $s->first_name }} {{ $s->last_name }}">
                        @endforeach
                    </datalist>
                </div>
                <button type="button" id="clearSearch" class="btn btn-outline-secondary">Clear</button>
            </div>

            {{-- Current Assignment Info --}}
            <div class="col-md-12 mt-2 {{ isset($student) ? '' : 'd-none' }}" id="currentInfo">
                <div class="alert alert-info">
                    <strong>Current Assignment:</strong><br>
                    Department: <span id="currentDepartment">{{ $student?->department ?? 'N/A' }}</span><br>
                    Year Level: <span id="currentYear">{{ $student?->year_level ?? 'N/A' }}</span><br>
                    Section: <span id="currentSection">{{ $student?->section ?? 'N/A' }}</span><br>
                    Strand: <span id="currentStrand">{{ $student?->strand ?? 'N/A' }}</span>
                </div>
            </div>

            {{-- Department --}}
            <div class="col-md-4">
                <label for="department" class="form-label">Department</label>
                <select name="department" id="department" class="form-select" required>
                    <option value="">-- Select Department --</option>
                    <option value="Kindergarten" {{ isset($student) && $student?->department == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                    <option value="Elementary" {{ isset($student) && $student?->department == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                    <option value="Junior High" {{ isset($student) && $student?->department == 'Junior High' ? 'selected' : '' }}>Junior High</option>
                    <option value="Senior High" {{ isset($student) && $student?->department == 'Senior High' ? 'selected' : '' }}>Senior High</option>
                </select>
            </div>

            {{-- Strand --}}
            <div class="col-md-4 {{ isset($student) && $student?->department == 'Senior High' ? '' : 'd-none' }}" id="strandGroup">
                <label for="strand" class="form-label">Strand</label>
                <select name="strand" id="strand" class="form-select">
                    <option value="">-- Select Strand --</option>
                    <option value="ABM" {{ isset($student) && $student?->strand == 'ABM' ? 'selected' : '' }}>ABM</option>
                    <option value="STEM" {{ isset($student) && $student?->strand == 'STEM' ? 'selected' : '' }}>STEM</option>
                    <option value="HUMSS" {{ isset($student) && $student?->strand == 'HUMSS' ? 'selected' : '' }}>HUMSS</option>
                    <option value="GAS" {{ isset($student) && $student?->strand == 'GAS' ? 'selected' : '' }}>GAS</option>
                </select>
            </div>

            {{-- Year Level --}}
            <div class="col-md-3" id="yearLevelGroup">
                <label for="year_level" class="form-label">Year Level</label>
                <select name="year_level" id="year_level" class="form-select" required></select>
            </div>

            {{-- Section --}}
            <div class="col-md-3" id="sectionGroup">
                <label for="section" class="form-label">Section</label>
                <select name="section" id="section" class="form-select" required></select>
            </div>

            {{-- Submit --}}
            <div class="col-12 mt-3">
                <button type="submit" class="btn btn-success">Assign Student</button>
            </div>
        </div>
    </form>
</div>

{{-- SweetAlert --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
});
</script>
@endif

<script>
document.addEventListener("DOMContentLoaded", function () {
    const departmentSelect = document.getElementById("department");
    const yearLevelSelect = document.getElementById("year_level");
    const sectionSelect = document.getElementById("section");
    const strandGroup = document.getElementById("strandGroup");

    const currentInfo = document.getElementById("currentInfo");
    const currentDepartment = document.getElementById("currentDepartment");
    const currentYear = document.getElementById("currentYear");
    const currentSection = document.getElementById("currentSection");
    const currentStrand = document.getElementById("currentStrand");

    const studentInput = document.getElementById('student_search');
    const studentIdInput = document.getElementById('student_id');
    const clearSearchBtn = document.getElementById('clearSearch');
    const datalistOptions = document.querySelectorAll('#studentList option');

    const data = {
        Kindergarten: { yearLevels: ["Kindergarten"], sections: { "Kindergarten": ["St. Mary"] } },
        Elementary: { yearLevels: ["1","2","3","4","5","6"], sections: {"1":["St. Therese"],"2":["St. Claire"],"3":["St. Francis"],"4":["St. John"],"5":["St. James"],"6":["St. Pedro Calungsod"]} },
        "Junior High": { yearLevels: ["7","8","9","10"], sections: {"7":["St. Mark"],"8":["St. Thomas"],"9":["St. Ignatius"],"10":["St. Vladimir"]} },
        "Senior High": { yearLevels: ["11","12"], sections: {"11":["St. Philomere"],"12":["St. Magdalene"]} }
    };

    function populateYearLevels(dept, selectedYear = null, selectedSection = null) {
        yearLevelSelect.innerHTML = "";
        sectionSelect.innerHTML = "";
        if (!dept || !data[dept]) return;

        data[dept].yearLevels.forEach(level => {
            const opt = document.createElement("option");
            opt.value = level;
            opt.textContent = level;
            if (selectedYear && selectedYear.toString() === level.toString()) opt.selected = true;
            yearLevelSelect.appendChild(opt);
        });

        const level = selectedYear ?? data[dept].yearLevels[0];
        if (data[dept].sections[level]) {
            data[dept].sections[level].forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                if (selectedSection && selectedSection === sec) opt.selected = true;
                sectionSelect.appendChild(opt);
            });
        }
    }

    // Department change
    departmentSelect.addEventListener("change", function () {
        const dept = this.value;
        if (dept === "Senior High") {
            strandGroup.classList.remove("d-none");
            document.getElementById("strand").setAttribute("required","true");
        } else {
            strandGroup.classList.add("d-none");
            document.getElementById("strand").removeAttribute("required");
        }
        populateYearLevels(dept);
    });

    yearLevelSelect.addEventListener("change", function () {
        const dept = departmentSelect.value;
        const level = this.value;
        sectionSelect.innerHTML = "";
        if (data[dept] && data[dept].sections[level]) {
            data[dept].sections[level].forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                sectionSelect.appendChild(opt);
            });
        }
    });

    // Student selection logic
    studentInput.addEventListener('input', function () {
        const val = this.value;
        const matched = Array.from(datalistOptions).find(opt => opt.value === val);
        if (matched) {
            studentIdInput.value = matched.dataset.id;
            currentDepartment.textContent = matched.dataset.department || "N/A";
            currentYear.textContent = matched.dataset.year || "N/A";
            currentSection.textContent = matched.dataset.section || "N/A";
            currentStrand.textContent = matched.dataset.strand || "N/A";
            currentInfo.classList.remove("d-none");

            departmentSelect.value = matched.dataset.department;
            populateYearLevels(matched.dataset.department, matched.dataset.year, matched.dataset.section);

            if (matched.dataset.department === "Senior High") {
                strandGroup.classList.remove("d-none");
                document.getElementById("strand").value = matched.dataset.strand;
            } else {
                strandGroup.classList.add("d-none");
            }
        } else {
            studentIdInput.value = '';
            currentInfo.classList.add("d-none");
        }
    });

    // Clear search
    clearSearchBtn.addEventListener("click", function () {
        studentInput.value = '';
        studentIdInput.value = '';
        currentInfo.classList.add("d-none");
        yearLevelSelect.innerHTML = '';
        sectionSelect.innerHTML = '';
    });

    // Prepopulate if controller passed a student
    @if(isset($student))
        const preselectedOption = Array.from(datalistOptions).find(opt => opt.dataset.id == "{{ $student?->id ?? '' }}");
        if (preselectedOption) {
            studentIdInput.value = preselectedOption.dataset.id;
            studentInput.value = preselectedOption.value;
            currentInfo.classList.remove("d-none");
            currentDepartment.textContent = "{{ $student?->department ?? 'N/A' }}";
            currentYear.textContent = "{{ $student?->year_level ?? 'N/A' }}";
            currentSection.textContent = "{{ $student?->section ?? 'N/A' }}";
            currentStrand.textContent = "{{ $student?->strand ?? 'N/A' }}";

            departmentSelect.value = "{{ $student?->department ?? '' }}";
            populateYearLevels("{{ $student?->department ?? '' }}", "{{ $student?->year_level ?? '' }}", "{{ $student?->section ?? '' }}");

            @if($student?->department == 'Senior High')
                strandGroup.classList.remove("d-none");
                document.getElementById("strand").value = "{{ $student?->strand ?? '' }}";
            @endif
        }
    @endif
});
</script>
@endsection
