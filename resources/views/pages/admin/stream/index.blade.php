@extends('pages.admin.admin-content')

@section('content')
<h2>All Subject Stream</h2>

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
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Assigned Students</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = 1;
        @endphp

        @foreach ($streams as $stream)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $stream['stream_name'] }}</td>
            <td>{{ $stream['student_count'] }}</td>
            <td>
                <a href="/admin/streams/{{ $stream['id'] }}/assign" class="btn btn-info btn-sm">Assign</a>
                <a href="/admin/streams/{{ $stream['id'] }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <form action="/admin/streams/{{ $stream['id'] }}" method="POST" style="display: inline;">
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
        $(document).prop('title', 'All Streams | Student Management System');
    });
</script>
@endsection