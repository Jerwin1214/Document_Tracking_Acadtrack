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
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'All Subjects | Student Management System');
        });
    </script>

@endsection
