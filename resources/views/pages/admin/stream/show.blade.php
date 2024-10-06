

@extends('pages.admin.admin-content')

@section('content')
    <h2>{{$stream->stream_name}}</h2>
    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Stream Details | Student Management System');
        });
    </script>
@endsection
