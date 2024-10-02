@extends('pages.admin.admin-content')

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
<h2>All Students</h2>
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
        $i = 1;
        @endphp

        @foreach ($students as $student)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
            <td>
                <a href="/admin/students/{{ $student->id }}" class="btn btn-primary btn-sm">View</a>
                <a href="/admin/students/{{ $student->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <form action="/admin/students/{{ $student->id }}" method="POST" style="display: inline;">
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
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Students | Student Management System');
    });
</script>

@endsection
