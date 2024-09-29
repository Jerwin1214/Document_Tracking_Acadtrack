@extends('pages.admin.admin-content')

@section('content')
    <h2>Edit {{$class->name}} - {{$class->year}}</h2>
    <form action="/admin/class/{{$class->id}}" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label for="grade" class="form-label">Grade</label>
            <select name="grade" id="grade" class="form-select">
                <option value="">-- Choose One --</option>
                @foreach ($grades as $grade)
                    <option
                        value="{{$grade->id}}" {{$class->grade_id==$grade->id ? 'selected' : ''}}>{{$grade->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="class_name" class="form-label">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" value="{{$class->name}}"
                   required>
        </div>

        <div class="mb-3">
            <label for="subject" class="form-label">Subject</label>
            <select name="subject" id="subject" class="form-select">
                <option value="">-- Choose One --</option>
                @foreach ($subjects as $subject)
                    <option
                        value="{{$subject->id}}" {{$class->subject_id == $subject->id ? 'selected' : ''}}>{{$subject->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="teacher" class="form-label">Teacher</label>
            <select name="teacher" id="teacher" class="form-select">
                <option value="">-- Choose One --</option>
                @foreach ($teachers as $teacher)
                    <option
                        value="{{$teacher->id}}" {{$class->teacher_id == $teacher->id ? 'selected' : ''}} >{{$teacher->salutation}} {{$teacher->first_name}} {{$teacher->last_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <select name="year" id="year" class="form-select">
                <option value="">-- Choose One --</option>
                <option
                    value="{{date("Y")-3}}" {{$class->year == date("Y")-3 ? 'selected' : ''}} >{{date("Y")-3}}</option>
                <option
                    value="{{date("Y")-2}}" {{$class->year == date("Y")-2 ? 'selected' : ''}}>{{date("Y")-2}}</option>
                <option
                    value="{{date("Y")-1}}" {{$class->year == date("Y")-1 ? 'selected' : ''}}>{{date("Y")-1}}</option>
                <option value="{{date("Y")}}" {{$class->year == date("Y") ? 'selected' : ''}}>{{date("Y")}}</option>
                <option
                    value="{{date("Y")+1}}" {{$class->year == date("Y")+1 ? 'selected' : ''}}>{{date("Y")+1}}</option>
                <option
                    value="{{date("Y")+2}}" {{$class->year == date("Y")+2 ? 'selected' : ''}}>{{date("Y")+2}}</option>
                <option
                    value="{{date("Y")+3}}" {{$class->year == date("Y")+3 ? 'selected' : ''}}>{{date("Y")+3}}</option>
            </select>
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-warning">Edit Class</button>
            <button type="reset" class="btn btn-outline-secondary">Clear</button>
        </div>

    </form>

    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Add New Class');
        })
    </script>
@endsection
