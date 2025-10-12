@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🧒 Kindergarten Grading</h2>
    <h5>{{ $student->first_name }} {{ $student->last_name }} - {{ $subject->name }}</h5>

    <form action="{{ route('teacher.grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <div class="row">
            @foreach(['language_literacy'=>'Language & Literacy','mathematics'=>'Mathematics','science_social'=>'Science & Social','social_emotional'=>'Social & Emotional','creative_arts_pe'=>'Creative Arts & PE'] as $field => $label)
                <div class="col-md-6 mb-3">
                    <label class="form-label">{{ $label }}</label>
                    <select name="grades[{{ $field }}]" class="form-select">
                        <option value="O">O</option>
                        <option value="VS">VS</option>
                        <option value="S">S</option>
                        <option value="NI">NI</option>
                    </select>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Save Grades</button>
    </form>
</div>
@endsection
