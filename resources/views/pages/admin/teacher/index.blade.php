@extends('pages.admin.admin-content')

@section('content')
<!-- Slotted content -->

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


<h2>All Teachers</h2>
<table class="table table-responsive">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subjects</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @php
        $i = ($teachers->currentpage() - 1) * $teachers->perpage() + 1;
        @endphp

        @foreach ($teachers as $teacher)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $teacher->salutation }} {{ $teacher->initials }} {{ $teacher->first_name }} {{ $teacher->last_name }}</td>
            <td>{{ $teacher->user->email }}</td>
            <td>
                <ul>
                    @foreach ($teacher->subjects as $subject)
                    <li title="{{$subject->name}}">{{$subject->code}}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <a href="/admin/teachers/{{ $teacher->id }}" class="btn btn-primary btn-sm">View</a>
                {{-- /admin/teachers/{{ $teacher->id }}/assign-class --}}
                <a class="btn btn-info btn-sm assign">Assign</a>
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
<div class="container">
    {{ $teachers->links() }}
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Teachers | Student Management System');

        // Assign button click event
        $(".assign").click(function() {
            Swal.fire({
                icon: "info",
                title: "This feature is not implemented yet!",
                showConfirmButton: false,
                timer: 1500
            });
        });
    });
</script>

@endsection