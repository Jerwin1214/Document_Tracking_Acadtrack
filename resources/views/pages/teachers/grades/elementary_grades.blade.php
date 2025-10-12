@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📚 Elementary Grading</h2>
    <h5>{{ $student->first_name }} {{ $student->last_name }}</h5>

    <form action="{{ route('teacher.grades.store') }}" method="POST">

        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <div class="row">
            @foreach(['filipino','english','reading_and_literacy','mathematics','science','makabansa','ap','mapeh','epp','tle','esp','gmrc'] as $field)
                <div class="col-md-4 mb-3">
                    <label class="form-label text-capitalize">{{ str_replace('_',' ', $field) }}</label>
                    <input type="number" name="grades[{{ $field }}]" class="form-control" min="0" max="100">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Save Grades</button>
    </form>
</div>
@endsection
