@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📚 My Classes</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($classes->isEmpty())
        <p class="text-muted">You are not assigned to any class yet.</p>
    @else
        <div class="row">
            @foreach($classes as $class)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title">
                                {{ $class->name ?? $class->year_level . ' - ' . $class->section }}
                            </h5>
                            <p class="card-text">
                                Department: {{ ucfirst($class->department ?? 'N/A') }}<br>
                                Students: {{ $class->students()->count() }}
                            </p>

                            {{-- Directly go to students list --}}
<a href="{{ route('teacher.grades.class.students', $class->id) }}" class="btn btn-primary">
    View Students
</a>


                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
