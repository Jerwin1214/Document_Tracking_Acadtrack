@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">📋 Select a Class to View Grades</h2>

    @if($classes->isEmpty())
        <p class="text-center text-muted fs-5 mt-5">No classes assigned to you.</p>
    @else
        <div class="row g-4">
            @foreach($classes as $class)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('teacher.grades.view.class.subjects', $class->id) }}" class="text-decoration-none">
                        <div class="card shadow-sm border-0 h-100 hover-scale">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                                <div class="mb-3 display-6">📘</div>
                                <h5 class="card-title fw-bold mb-1">{{ $class->year_level }} - {{ $class->section }}</h5>
                                <p class="card-text text-muted">Click to view all subjects & grades</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    /* Hover effect for cards */
    .hover-scale {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
