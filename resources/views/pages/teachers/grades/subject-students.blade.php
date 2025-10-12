@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📘 Students in {{ $subject->name }}</h2>

    @if($students->isEmpty())
        <p class="text-muted">No students enrolled in this subject.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover shadow-sm align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>LRN</th>
                        <th>Full Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $student->lrn }}</td>
                            <td>
                                {{ $student->last_name }},
                                {{ $student->first_name }}
                                {{ $student->middle_name ? $student->middle_name : '' }}
                            </td>
                            <td>
                                <a href="{{ route('teacher.grades.create', [
        'subject' => $subject->id,
        'student' => $student->id
    ]) }}"
    class="btn btn-sm btn-primary">
    Grade
</a>


                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
