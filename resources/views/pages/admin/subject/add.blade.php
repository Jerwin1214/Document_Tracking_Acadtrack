@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Header with Title + Bulk Upload --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fa-solid fa-book-open me-2"></i> Add New Subject
        </h2>

        <form action="/admin/subjects/upload" method="post" enctype="multipart/form-data" id="uploadForm">
            @csrf
            <input type="file" name="file" id="fileInput" accept=".xls, .xlsx" hidden onchange="submitForm()"/>
            <button type="button" class="btn btn-outline-primary shadow-sm" onclick="document.getElementById('fileInput').click()">
                <i class="fa-solid fa-file-arrow-up me-1"></i> Bulk Upload
            </button>
            <x-form-error name="file"/>
        </form>
    </div>

    {{-- Add Subject Form --}}
    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-4">
            <form action="/admin/subjects" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label fw-semibold">
                        <i class="fa-solid fa-book me-1 text-secondary"></i> Subject Name
                    </label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter subject name" required>
                    <x-form-error name="name"/>
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label fw-semibold">
                        <i class="fa-solid fa-code me-1 text-secondary"></i> Subject Code
                    </label>
                    <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="Enter subject code" required>
                    <x-form-error name="code"/>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label fw-semibold">
                        <i class="fa-solid fa-align-left me-1 text-secondary"></i> Description
                    </label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Write a short description...">{{ old('description') }}</textarea>
                    <x-form-error name="description"/>
                </div>

                {{-- Level Select --}}
                <div class="mb-3">
                    <label for="level" class="form-label fw-semibold">Level</label>
                    <select name="level" id="level" class="form-select shadow-sm" required>
                        <option value="">-- Choose Level --</option>
                        <option value="kindergarten" {{ old('level') == 'kindergarten' ? 'selected' : '' }}>Kindergarten</option>
                        <option value="elementary" {{ old('level') == 'elementary' ? 'selected' : '' }}>Elementary</option>
                        <option value="junior_high" {{ old('level') == 'junior_high' ? 'selected' : '' }}>Junior High</option>
                        <option value="senior_high" {{ old('level') == 'senior_high' ? 'selected' : '' }}>Senior High</option>
                    </select>
                    <x-form-error name="level"/>
                </div>

                {{-- Grade/Strand Select --}}
                <div class="mb-3 d-none" id="grade-container">
                    <label for="grade" class="form-label fw-semibold">Grade / Strand</label>
                    <select name="grade" id="grade" class="form-select shadow-sm">
                        <option value="">-- Choose --</option>
                        {{-- dynamically populated --}}
                    </select>
                    <x-form-error name="grade"/>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="reset" class="btn btn-light border">
                        <i class="fa-solid fa-eraser me-1"></i> Clear
                    </button>
                    <button type="submit" class="btn btn-primary shadow">
                        <i class="fa-solid fa-plus-circle me-1"></i> Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
$(document).ready(function () {
    $(document).prop('title', 'Add New Subject | Student Management System');

    function populateGrades(level) {
        const grades = {
            'elementary': ['1','2','3','4','5','6'],
            'junior_high': ['7','8','9','10'],
            'senior_high': ['11-STEM','11-ABM','11-HUMSS','11-GAS','12-STEM','12-ABM','12-HUMSS','12-GAS']
        };
        const $gradeSelect = $('#grade');
        $gradeSelect.html('<option value="">-- Choose --</option>');
        if (grades[level]) {
            grades[level].forEach(g => $gradeSelect.append(`<option value="${g}">${g}</option>`));
            $('#grade-container').removeClass('d-none');
        } else {
            $('#grade-container').addClass('d-none');
        }
    }

    $('#level').change(function() {
        const level = $(this).val();
        populateGrades(level);
    });
});

function submitForm() {
    const fileInput = document.getElementById('fileInput');
    if (fileInput.files.length > 0) {
        document.getElementById('uploadForm').submit();
    }
}
</script>
@endsection
