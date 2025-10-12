@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📝 Grading Form</h2>

    <h5>
        {{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}
    </h5>
    <p>Department: {{ ucfirst($department) }}</p>
    <p>Class: {{ $class->year_level ?? $class->name }} - {{ $class->section }}
        @if($class->strand) ({{ $class->strand }}) @endif
    </p>

    <form action="{{ route('teacher.grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="class_id" value="{{ $class->id }}">
        <input type="hidden" name="department" value="{{ $department }}">
        {{-- <input type="hidden" name="school_year" value="{{ $schoolYear }}"> --}}
        <input type="hidden" name="grade_level" value="{{ $class->year_level }}">
        <input type="hidden" name="strand" value="{{ $class->strand }}">

        <div class="row">
            @foreach($gradingFields as $column => $label)
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $label }}</label>

                    @if($department === 'kindergarten')
                        <div class="d-flex gap-2">
                            @for($q=1; $q<=4; $q++)
                                @php
                                    $existingValue = null;
                                    if(isset($existingGrade) && isset($existingGrade->{$column}[$q])){
                                        $existingValue = $existingGrade->{$column}[$q];
                                        if(is_array($existingValue)){
                                            $existingValue = implode(', ', $existingValue);
                                        }
                                    }
                                @endphp
                                <select name="grades[{{ $column }}][{{ $q }}]" class="form-select">
                                    <option value="">--Q{{ $q }}--</option>
                                    @foreach(['O','VS','S','NI'] as $option)
                                        <option value="{{ $option }}"
                                            @if($existingValue == $option) selected @endif>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            @endfor
                        </div>
                        <small class="text-muted">Grades per quarter (leave empty if not available)</small>
                    @else
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Quarter / Semester</label>
                                <select name="grading_quarter" class="form-select" required>
                                    @php
                                        $terms = ($department === 'senior_high')
                                            ? ['1st' => '1st Semester', '2nd' => '2nd Semester']
                                            : ['1st' => '1st Quarter', '2nd' => '2nd Quarter', '3rd' => '3rd Quarter', '4th' => '4th Quarter'];
                                    @endphp
                                    @foreach($terms as $key => $termLabel)
                                        <option value="{{ $key }}"
                                            {{ isset($existingGrade->grading_quarter) && $existingGrade->grading_quarter == $key ? 'selected' : '' }}>
                                            {{ $termLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <input type="number" min="0" max="100" step="0.01"
                               name="grades[{{ $column }}]"
                               class="form-control"
                               value="{{ isset($existingGrade->{$column}) ? number_format($existingGrade->{$column},2,'.','') : '' }}">
                        <small class="text-muted">Enter grade (0-100)</small>
                    @endif
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success mt-3">Save Grades</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @elseif(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endsection
