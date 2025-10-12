@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fa-solid fa-pen-to-square me-2"></i> Edit Class - {{ $class->name }}
        </h2>
        <a href="{{ route('admin.classes.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Back to Classes
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="card shadow-sm rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.classes.update', $class->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="row g-3">
                    {{-- Department --}}
                    <div class="col-md-4">
                        <label for="department" class="form-label fw-semibold">Department</label>
                        <select name="department" id="department" class="form-select shadow-sm" required>
                            <option value="">-- Select Department --</option>
                            <option value="Kindergarten" {{ $class->department == 'Kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                            <option value="Elementary" {{ $class->department == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                            <option value="Junior High" {{ $class->department == 'Junior High' ? 'selected' : '' }}>Junior High</option>
                            <option value="Senior High" {{ $class->department == 'Senior High' ? 'selected' : '' }}>Senior High</option>
                        </select>
                    </div>

                    {{-- Year Level --}}
                    <div class="col-md-3" id="yearLevelGroup">
                        <label for="year_level" class="form-label fw-semibold">Year Level</label>
                        <select name="year_level" id="year_level" class="form-select shadow-sm" required></select>
                    </div>

                    {{-- Section --}}
                    <div class="col-md-3" id="sectionGroup">
                        <label for="section" class="form-label fw-semibold">Section</label>
                        <select name="section" id="section" class="form-select shadow-sm" required></select>
                    </div>

                    {{-- Teacher --}}
                    <div class="col-md-4">
                        <label for="teacher_id" class="form-label fw-semibold">Adviser / Teacher</label>
                        <select name="teacher_id" id="teacher_id" class="form-select shadow-sm" required>
                            <option value="">-- Choose One --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->salutation }} {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- School Year --}}
                    <div class="col-md-3">
                        <label for="year" class="form-label fw-semibold">School Year</label>
                        <select name="year" id="year" class="form-select shadow-sm" required>
                            @for($i = -3; $i <= 3; $i++)
                                @php
                                    $startYear = date('Y') + $i;
                                    $endYear = $startYear + 1;
                                    $sy = $startYear.'-'.$endYear;
                                @endphp
                                <option value="{{ $sy }}" {{ $class->year == $sy ? 'selected' : '' }}>
                                    {{ $sy }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-warning shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Update Class
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Populate Year Levels & Sections --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const departmentSelect = document.getElementById("department");
    const yearLevelSelect = document.getElementById("year_level");
    const sectionSelect = document.getElementById("section");

    const data = {
        Kindergarten: { yearLevels: ["Kindergarten"], sections: { "Kindergarten": ["St. Mary"] } },
        Elementary: { yearLevels: ["1","2","3","4","5","6"], sections: { "1":["St. Therese"],"2":["St. Claire"],"3":["St. Francis"],"4":["St. John"],"5":["St. James"],"6":["St. Pedro Calungsod"] } },
        "Junior High": { yearLevels: ["7","8","9","10"], sections: { "7":["St. Mark"],"8":["St. Thomas"],"9":["St. Ignatius"],"10":["St. Vladimir"] } },
        "Senior High": { yearLevels: ["11","12"], sections: { "11":["St. Philomere"],"12":["St. Magdalene"] } }
    };

    function populateYearLevels() {
        const dept = departmentSelect.value;
        yearLevelSelect.innerHTML = "";
        sectionSelect.innerHTML = "";
        if(dept && data[dept]) {
            data[dept].yearLevels.forEach(level => {
                const opt = document.createElement("option");
                opt.value = level;
                opt.textContent = level;
                if(level == '{{ $class->year_level }}') opt.selected = true;
                yearLevelSelect.appendChild(opt);
            });

            const firstLevel = yearLevelSelect.value || data[dept].yearLevels[0];
            data[dept].sections[firstLevel].forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                if(sec == '{{ $class->section }}') opt.selected = true;
                sectionSelect.appendChild(opt);
            });
        }
    }

    function populateSections() {
        const dept = departmentSelect.value;
        const level = yearLevelSelect.value;
        sectionSelect.innerHTML = "";
        if(data[dept] && data[dept].sections[level]) {
            data[dept].sections[level].forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                if(sec == '{{ $class->section }}') opt.selected = true;
                sectionSelect.appendChild(opt);
            });
        }
    }

    departmentSelect.addEventListener("change", populateYearLevels);
    yearLevelSelect.addEventListener("change", populateSections);

    // Initial population
    populateYearLevels();
});
</script>
@endsection
