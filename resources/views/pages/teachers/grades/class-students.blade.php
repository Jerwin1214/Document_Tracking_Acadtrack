@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">👩‍🏫 Students in {{ $class->name ?? $class->section }}</h2>
    <p>Department: {{ ucfirst($class->department ?? 'N/A') }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>LRN</th>
                <th>Name</th>
                <th>Section</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->lrn }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->section ?? '-' }}</td>
                    <td>
<a href="{{ route('teacher.grades.create', ['student' => $student->id]) }}"
   class="btn btn-sm btn-primary">
   Grade Student
</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No students assigned to this class.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
