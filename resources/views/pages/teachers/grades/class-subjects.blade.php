@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📖 Subjects for {{ $class->name ?? $class->grade_level . ' - ' . $class->section }}</h2>

    @if($subjects->isEmpty())
        <div class="alert alert-warning text-center">
            🙁 No subjects have been assigned to you in this class.
        </div>
    @else
        <div class="row">
           @foreach($subjects as $subject)
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column text-center">
                <h5 class="card-title text-primary fw-bold">{{ $subject->name }}</h5>
                <p class="text-muted mb-3">
                    Students: {{ $class->students()->count() }}
                </p>
                <a href="{{ isset($class, $subject) ? route('teacher.grades.class.subject.students', ['class' => $class->id, 'subject' => $subject->id]) : '#' }}">
    View Students
</a>

            </div>
        </div>
    </div>
@endforeach

        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('teacher.grades.index') }}" class="btn btn-secondary">
            ← Back to My Classes
        </a>
    </div>
</div>
@endsection
