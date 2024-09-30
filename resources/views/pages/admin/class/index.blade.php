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
            $i = 1;
        @endphp

        @foreach ($classes as $class)
            <tr>
                @php
                    $teacher = \App\Models\Teacher::find($class->teacher_id);
                    $grade = \App\Models\Grade::find($class->grade_id);
                    $subject = \App\Models\Subject::find($class->subject_id);
                @endphp

                <td>{{ $i }}</td>
                <td>{{ $grade->name }}</td>
                <td>{{ $class->name }}</td>
                <td>{{$subject->code}}</td>
                <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                <td>{{ $class->year }}</td>
                <td>{{$class->students_count}}</td>
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
    <!--  -->

    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'All Classes | Student Management System');
        });
    </script>

@endsection
