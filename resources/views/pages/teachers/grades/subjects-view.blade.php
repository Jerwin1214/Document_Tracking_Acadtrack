@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">
        📖 Subjects for {{ $class->year_level }} - {{ $class->section }}
        @if($class->strand) ({{ $class->strand }}) @endif
    </h2>

    @if($subjects->isEmpty())
        <div class="alert alert-warning text-center fs-5">
            🙁 No subjects have been assigned to you in this class.
        </div>
    @else
        <div class="row g-4">
            @foreach($subjects as $subject)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('teacher.grades.view.class.subject.grades', [
                        'class' => $class->id,
                        'subject' => $subject->id
                    ]) }}" class="text-decoration-none">
                        <div class="card shadow-sm h-100 hover-scale border-0">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-3 display-6 text-primary">📚</div>
                                <h5 class="card-title fw-bold text-dark">{{ $subject->name }}</h5>
                                <p class="card-text text-muted mb-3">
                                    Students: {{ $class->students->count() }}
                                </p>
                                <button class="btn btn-outline-primary btn-sm mt-auto">
                                    View Students & Grades
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4 text-center">
        <a href="{{ route('teacher.grades.view.select-class') }}" class="btn btn-secondary">
            ← Back to Classes
        </a>
    </div>
</div>

<style>
    /* Card hover effect */
    .hover-scale {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-body button {
        cursor: pointer;
    }
</style>
@endsection
