@extends('pages.admin.admin-content');
<!-- Slotted content -->
@section('content')
<h2>Edit {{$subject->name}}</h2>
<form action="/admin/subjects/{{$subject->id}}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="name" class="form-label">Subject Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{$subject->name}}" required>
        <x-form-error name="name" />
    </div>

    <div class="mb-3">
        <label for="code" class="form-label">Subject Code</label>
        <input type="text" class="form-control" id="code" name="code" value="{{$subject->code}}" required>
        <x-form-error name="code" />
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{$subject->description}}</textarea>
        <x-form-error name="description" />
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-warning">Edit Subject</button>
        <button type="reset" class="btn btn-secondary">Clear</button>
    </div>
</form>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Edit Subject | Student Management System');
    });
</script>

@endsection