@extends('pages.admin.admin-content')

@section('content')
<!-- Slotted content -->
<h2>All Students</h2>
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
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
            <td>{{ $student->user->email }}</td>
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
@endsection