@extends('pages.admin.admin-content')

@section('content')
<h2>Add New Class</h2>

<form action="/admin/class" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label for="grade" class="form-label">Grade</label>
        <select name="grade" id="grade" class="form-select">
            <option value="">-- Choose One --</option>
            @foreach ($grades as $grade)
            <option value="{{$grade->id}}">{{$grade->name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="class_name" class="form-label">Class Name</label>
        <input type="text" class="form-control" id="class_name" name="class_name" required>
    </div>

    <div class="mb-3">
        <label for="subject_stream" class="form-label">Subject Stream</label>
        <select name="subject_stream" id="subject_stream" class="form-select">
            <option value="">-- Choose One --</option>
            @foreach ($streams as $stream)
            <option value="{{$stream->id}}">{{$stream->stream_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="teacher" class="form-label">Teacher</label>
        <select name="teacher" id="teacher" class="form-select">
            <option value="">-- Choose One --</option>
            @foreach ($teachers as $teacher)
            <option value="{{$teacher->id}}">{{$teacher->salutation}} {{$teacher->first_name}} {{$teacher->last_name}}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="year" class="form-label">Year</label>
        <select name="year" id="year" class="form-select">
            <option value="">-- Choose One --</option>
            <option value="{{date("Y")-3}}">{{date("Y")-3}}</option>
            <option value="{{date("Y")-2}}">{{date("Y")-2}}</option>
            <option value="{{date("Y")-1}}">{{date("Y")-3}}</option>
            <option value="{{date("Y")}}">{{date("Y")}}</option>
            <option value="{{date("Y")+1}}">{{date("Y")+1}}</option>
            <option value="{{date("Y")+2}}">{{date("Y")+2}}</option>
            <option value="{{date("Y")+3}}">{{date("Y")+3}}</option>
        </select>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Add Class</button>
        <button type="reset" class="btn btn-outline-secondary">Clear</button>
    </div>

</form>

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Add New Class');
    })
</script>

@endsection
