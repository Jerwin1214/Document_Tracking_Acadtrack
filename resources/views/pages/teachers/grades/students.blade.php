@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">👨‍🎓 Students in {{ $subject->name }}</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Grade Level</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                <tr>
                    <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                    <td>{{ $student->grade_level }}</td>
                    <td>
                        <a href="{{ route('teacher.grades.create', [$student->id, $subject->id]) }}"
   class="btn btn-sm btn-primary">Grade</a>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">🙁 No students found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
