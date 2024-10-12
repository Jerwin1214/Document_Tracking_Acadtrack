@extends('pages.teachers.teacher-content');
<!-- Slotted content -->
@section('content')
<h2>New Announcement</h2>
<form action="/teacher/announcements/" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Date</label>
        <input type="date" class="form-control" id="date" name="date" value="{{date('Y-m-d')}}" required readonly>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Post Announcement</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
    </div>
</form>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Post new Announcement | Student Management System');
    });
</script>

@endsection