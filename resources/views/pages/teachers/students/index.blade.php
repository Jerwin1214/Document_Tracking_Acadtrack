@extends('pages.teachers.teacher-content')

@section('content')
<!-- Popup messages -->
@if (session('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

@if (session('warning'))
<script>
    Swal.fire({
        icon: "warning",
        title: "{{ session('warning') }}",
        showConfirmButton: true,
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        showConfirmButton: true,
    });
</script>
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