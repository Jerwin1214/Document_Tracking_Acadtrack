@extends('pages.admin.admin-content')

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
<h2>All Classes</h2>
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Grade</th>
            <th>Class Name</th>
            <th>Subject</th>
            <th>Teacher</th>
            <th>Year</th>
            <th>No. of Students</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = ($classes->currentpage() - 1) * $classes->perpage() + 1;
        @endphp

        @foreach ($classes as $class)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $class->grade_name }}</td>
            <td>{{ $class->name }}</td>
            <td>{{ $class->subject_stream_name }}</td>
            <td>{{ $class->teacher_first_name }} {{ $class->teacher_last_name }}</td>
            <td>{{ $class->year }}</td>
            <td>{{ $class->students_count }}</td>
            <td>
                <a href="/admin/class/{{ $class->id }}" class="btn btn-primary btn-sm">View</a>
                <a href="/admin/class/{{ $class->id }}/assign" class="btn btn-info btn-sm">Assign</a>
                <a href="/admin/class/{{ $class->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <form action="/admin/class/{{ $class->id }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @php
        $i++;
        @endphp
        @endforeach
    </tbody>

</table>
<div class="container">
    {{ $classes->links() }}
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Classes | Student Management System');
    });
</script>

@endsection