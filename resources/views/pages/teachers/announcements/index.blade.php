@extends('pages.teachers.teacher-content');
<!-- Slotted content -->
@section('content')
<h2>All Announcement</h2>
@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

@if (session('warning'))
<x-popup-message type="warning" :message="session('warning')" />
@endif

@if (session('error'))
<x-popup-message type="error" :message="session('error')" />
@endif
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Announcement | Student Management System');
    });
</script>

@endsection