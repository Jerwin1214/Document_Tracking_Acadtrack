@extends('pages.teachers.teacher-content')

@section('content')

<!-- Popup messages -->
@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

@if (session('warning'))
<x-popup-message type="warning" :message="session('warning')" />
@endif

@if (session('error'))
<x-popup-message type="error" :message="session('error')" />
@endif
<!--  -->

<!-- Slotted content -->
<h2>Class Students</h2>
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = ($students->currentpage() - 1) * $students->perpage() + 1;

        @endphp

        @foreach ($students as $student)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
            <td>
                <a href="/teacher/students/{{ $student->id }}" class="btn btn-primary btn-sm">View</a>
                <a href="/teacher/students/{{ $student->id }}/assign" class="btn btn-info btn-sm">Assign</a>
                <a href="/teacher/students/{{ $student->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
            </td>
        </tr>
        @php
        $i++;
        @endphp
        @endforeach

    </tbody>
</table>
<div class="container">
    {{ $students->links() }}
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Students | Student Management System');
    });
</script>

@endsection