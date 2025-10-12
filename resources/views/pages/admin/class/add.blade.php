@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fa-solid fa-school me-2"></i> Add New Class
        </h2>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Form Card --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('admin.classes.store') }}" method="post">
                @csrf

                <div class="row g-4">

                    {{-- Department --}}
                    <div class="col-md-4">
                        <label for="department" class="form-label fw-semibold">
                            <i class="fa-solid fa-building me-1 text-secondary"></i> Department
                        </label>
                        <select name="department" id="department" class="form-select shadow-sm" required>
                            <option value="">-- Select Department --</option>
                            <option value="Kindergarten">Kindergarten</option>
                            <option value="Elementary">Elementary</option>
                            <option value="Junior High">Junior High</option>
                            <option value="Senior High">Senior High</option>
                        </select>
                    </div>

                    {{-- Year Level --}}
                    <div class="col-md-3" id="yearLevelGroup">
                        <label for="year_level" class="form-label fw-semibold">
                            <i class="fa-solid fa-layer-group me-1 text-secondary"></i> Year Level
                        </label>
                        <select name="year_level" id="year_level" class="form-select shadow-sm" required></select>
                    </div>

                    {{-- Section --}}
                    <div class="col-md-3" id="sectionGroup">
                        <label for="section" class="form-label fw-semibold">
                            <i class="fa-solid fa-users me-1 text-secondary"></i> Section
                        </label>
                        <select name="section" id="section" class="form-select shadow-sm" required></select>
                    </div>

                    {{-- Teacher --}}
                    <div class="col-md-4">
                        <label for="teacher_id" class="form-label fw-semibold">
                            <i class="fa-solid fa-user-tie me-1 text-secondary"></i> Adviser / Teacher
                        </label>
                        <select name="teacher_id" id="teacher_id" class="form-select shadow-sm" required>
                            <option value="">-- Choose One --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->salutation }} {{ $teacher->first_name }} {{ $teacher->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Strand (Senior High only) --}}
                    <div class="col-md-3" id="strandGroup" style="display: none;">
                        <label for="strand_id" class="form-label fw-semibold">
                            <i class="fa-solid fa-diagram-project me-1 text-secondary"></i> Strand
                        </label>
                        <select name="subject_stream_id" id="strand_id" class="form-select shadow-sm">
                            <option value="">-- Select Strand --</option>
                            @foreach($streams as $stream)
                                <option value="{{ $stream->id }}">{{ $stream->stream_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- School Year --}}
                    <div class="col-md-3">
                        <label for="year" class="form-label fw-semibold">
                            <i class="fa-solid fa-calendar-days me-1 text-secondary"></i> School Year
                        </label>
                        <select name="year" id="year" class="form-select shadow-sm" required>
                            @for($i = -3; $i <= 3; $i++)
                                @php
                                    $startYear = date('Y') + $i;
                                    $endYear = $startYear + 1;
                                @endphp
                                <option value="{{ $startYear }}-{{ $endYear }}">{{ $startYear }}-{{ $endYear }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fa-solid fa-plus-circle"></i> Add Class
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const departmentSelect = document.getElementById("department");
    const yearLevelSelect = document.getElementById("year_level");
    const sectionSelect = document.getElementById("section");
    const strandGroup = document.getElementById("strandGroup");

    const data = {
        Kindergarten: {
            yearLevels: ["Kindergarten"],
            sections: { "Kindergarten": ["St. Mary"] }
        },
        Elementary: {
            yearLevels: ["1","2","3","4","5","6"],
            sections: {
                "1":["St. Therese"],"2":["St. Claire"],"3":["St. Francis"],
                "4":["St. John"],"5":["St. James"],"6":["St. Pedro Calungsod"]
            }
        },
        "Junior High": {
            yearLevels: ["7","8","9","10"],
            sections: {
                "7":["St. Mark"],"8":["St. Thomas"],"9":["St. Ignatius"],"10":["St. Vladimir"]
            }
        },
        "Senior High": {
            yearLevels: ["11","12"],
            sections: { "11":["St. Philomere"],"12":["St. Magdalene"] }
        }
    };

    function toggleStrand(department) {
        if(department === "Senior High"){
            strandGroup.style.display = "block";
        } else {
            strandGroup.style.display = "none";
            document.getElementById("strand_id").value = "";
        }
    }

    function populateYearLevels(dept){
        yearLevelSelect.innerHTML = "";
        if(dept && data[dept]){
            data[dept].yearLevels.forEach(level => {
                const opt = document.createElement("option");
                opt.value = level;
                opt.textContent = level;
                yearLevelSelect.appendChild(opt);
            });
        }
    }

    function populateSections(dept, level){
        sectionSelect.innerHTML = "";
        if(dept && level && data[dept].sections[level]){
            data[dept].sections[level].forEach(sec => {
                const opt = document.createElement("option");
                opt.value = sec;
                opt.textContent = sec;
                sectionSelect.appendChild(opt);
            });
        }
    }

    departmentSelect.addEventListener("change", function () {
        const dept = this.value;
        populateYearLevels(dept);
        const firstLevel = yearLevelSelect.value;
        populateSections(dept, firstLevel);
        toggleStrand(dept);
    });

    yearLevelSelect.addEventListener("change", function () {
        const dept = departmentSelect.value;
        populateSections(dept, this.value);
    });
});
</script>
@endsection
