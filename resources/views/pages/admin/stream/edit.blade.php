
@extends('pages.admin.admin-content')

@section('content')
    <h2>Edit {{$stream->stream_name}}</h2>
    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Edit Stream | Student Management System');
        });
    </script>
@endsection
