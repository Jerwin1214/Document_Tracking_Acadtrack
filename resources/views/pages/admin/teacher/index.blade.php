@extends('pages.admin.admin-content')

@section('content')
<!-- Slotted content -->
<h2>All Teachers</h2>
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

        @foreach ($teachers as $teacher)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $teacher->salutation }} {{ $teacher->initials }} {{ $teacher->first_name }} {{ $teacher->last_name }}</td>
            <td>{{ $teacher->user->email }}</td>
            <td>
                <a href="/admin/teachers/{{ $teacher->id }}" class="btn btn-primary btn-sm">View</a>
                <a href="/admin/teachers/{{ $teacher->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <form action="/admin/teachers/{{ $teacher->id }}" method="POST" style="display: inline;">
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