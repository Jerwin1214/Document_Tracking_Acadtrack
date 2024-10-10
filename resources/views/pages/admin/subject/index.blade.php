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
<h2>All Subjects</h2>
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Code</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = ($subjects->currentpage() - 1) * $subjects->perpage() + 1;
        @endphp

        @foreach ($subjects as $subject)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $subject->name }}</td>
            <td>{{ $subject->code }}</td>
            <td>
                <a href="/admin/subjects/{{ $subject->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                <form action="/admin/subjects/{{ $subject->id }}" method="POST" style="display: inline;">
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
    {{ $subjects->links() }}
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Subjects | Student Management System');
    });
</script>

@endsection