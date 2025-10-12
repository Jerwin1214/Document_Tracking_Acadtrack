@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fa-solid fa-chalkboard-teacher me-2"></i> Assign Subjects to Teachers
        </h2>
    </div>

    {{-- Assign Form --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body">
            <form action="{{ route('admin.subjects.assign') }}" method="post">
                @csrf

                {{-- Teacher Select --}}
                <h5 class="mb-3 fw-semibold text-secondary">👨‍🏫 Teacher Details</h5>
                <div class="mb-4">
                    <label for="teachers" class="form-label fw-semibold">Select Teacher</label>
                    <select name="teacher" id="teachers" class="form-select shadow-sm" required>
                        <option value="">-- Choose One --</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">
                                {{ $teacher->salutation }} {{ $teacher->first_name }} {{ $teacher->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-form-error name="teacher"/>
                </div>

                {{-- Level Select --}}
                <div class="mb-4">
                    <label for="level" class="form-label fw-semibold">Select Level</label>
                    <select name="level" id="level" class="form-select shadow-sm" required>
                        <option value="">-- Choose Level --</option>
                        <option value="kindergarten">Kindergarten</option>
                        <option value="elementary">Elementary</option>
                        <option value="junior_high">Junior High</option>
                        <option value="senior_high">Senior High</option>
                    </select>
                </div>

                {{-- Grade/Strand Select --}}
                <div class="mb-4 d-none" id="grade-container">
                    <label for="grade" class="form-label fw-semibold">Select Grade</label>
                    <select name="grade" id="grade" class="form-select shadow-sm">
                        <option value="">-- Choose Grade --</option>
                    </select>
                </div>

                <div class="mb-4 d-none" id="strand-container">
                    <label for="strand" class="form-label fw-semibold">Select Strand</label>
                    <select name="strand" id="strand" class="form-select shadow-sm">
                        <option value="">-- Choose Strand --</option>
                        <option value="STEM">STEM</option>
                        <option value="ABM">ABM</option>
                        <option value="HUMSS">HUMSS</option>
                        <option value="GAS">GAS</option>
                    </select>
                </div>

                {{-- Subjects Checkboxes --}}
                <h5 class="mb-3 fw-semibold text-secondary">📘 Subjects</h5>
                <div class="row g-3" id="subjects-container">
                    {{-- dynamically populated --}}
                </div>

                {{-- Actions --}}
                <div class="d-flex justify-content-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fa-solid fa-rotate-left"></i> Clear
                    </button>
                    <button type="submit" class="btn btn-primary shadow-sm" disabled>
                        <i class="fa-solid fa-paper-plane"></i> Assign
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
$(document).ready(function () {
    const $assignButton = $("button[type=submit]");

    function loadGrades(level) {
        const $grade = $('#grade');
        $grade.html('<option value="">-- Choose Grade --</option>');

        if(level === 'elementary') {
            ['1','2','3','4','5','6'].forEach(g => $grade.append(`<option value="${g}">${g}</option>`));
            $('#grade-container').removeClass('d-none');
            $('#strand-container').addClass('d-none');
        } else if(level === 'junior_high') {
            ['7','8','9','10'].forEach(g => $grade.append(`<option value="${g}">${g}</option>`));
            $('#grade-container').removeClass('d-none');
            $('#strand-container').addClass('d-none');
        } else if(level === 'senior_high') {
            ['11','12'].forEach(g => $grade.append(`<option value="${g}">${g}</option>`));
            $('#grade-container').removeClass('d-none');
            $('#strand-container').removeClass('d-none');
        } else {
            $('#grade-container, #strand-container').addClass('d-none');
        }
    }

    function loadSubjects() {
        const teacher = $('#teachers').val();
        const level = $('#level').val();
        const grade = $('#grade').val() || null;
        const strand = $('#strand').val() || null;
        const $container = $('#subjects-container');
        $container.html('');

        if(!teacher || !level) return;

        $.ajax({
            url: "{{ route('admin.subjects.getSubjects') }}",
            type: "GET",
            data: { teacher: teacher, level: level, grade: grade, strand: strand },
            success: function(subjects) {
                if (subjects.length === 0) {
                    $container.html('<p class="text-muted">No subjects found.</p>');
                } else {
                    subjects.forEach((subj, index) => {
                        $container.append(`
                            <div class="col-md-6">
                                <div class="card h-100 shadow-sm border-0 subject-card">
                                    <div class="card-body d-flex align-items-center">
                                        <div class="form-check w-100">
                                            <input class="form-check-input me-2" type="checkbox"
                                                   value="${subj.id}" name="subjects[]" id="subject-${index}"
                                                   ${subj.assigned ? 'checked' : ''}>
                                            <label class="form-check-label fw-medium d-flex justify-content-between align-items-center w-100" for="subject-${index}">
                                                <span>${subj.name}</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                }
                checkSelection();
            }
        });
    }

    function checkSelection() {
        const anyChecked = $("input[name='subjects[]']").is(':checked');
        $assignButton.prop('disabled', !anyChecked);
    }

    $('#level').change(function() {
        const level = $(this).val();
        $('#subjects-container').html('');
        if(['elementary','junior_high','senior_high'].includes(level)) {
            $('#grade-container').removeClass('d-none');
            loadGrades(level);
        } else {
            $('#grade-container, #strand-container').addClass('d-none');
            loadSubjects();
        }
    });

    // Trigger subject load after selecting grade or strand (for SHS core + specialization)
    $('#grade, #teachers, #strand').change(loadSubjects);

    $(document).on('change', "input[name='subjects[]']", checkSelection);
});
</script>

<style>
.subject-card { cursor: pointer; transition: all 0.2s ease-in-out; }
.subject-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }
.form-check-input { cursor: pointer; }
</style>
@endsection
