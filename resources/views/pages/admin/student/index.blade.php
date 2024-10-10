@extends('pages.admin.admin-content')

@section('content')

<!-- Popup messages -->
@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

@if (session('info'))
<x-popup-message type="info" :message="session('info')" />
@endif

@if (session('warning'))
<x-popup-message type="warning" :message="session('warning')" />
@endif

@if (session('error'))
<x-popup-message type="error" :message="session('error')" />
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
        $i = ($students->currentpage() - 1) * $students->perpage() + 1;
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
<div class="container">
    {{$students->links()}}
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Students | Student Management System');
    });
</script>

@endsection