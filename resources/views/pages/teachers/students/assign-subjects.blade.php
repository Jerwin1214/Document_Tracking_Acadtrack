@extends('pages.teachers.teacher-content');
<!-- Slotted content -->
@section('content')
<h2>Change Subjects for {{$student->first_name}} {{$student->last_name}}</h2>
<form action="/teacher/students/{{$student->id}}/assign" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    @method('PATCH')
    <div class="mb-3">
        <label for="student" class="form-label">Student</label>
        <input type="text" class="form-control" id="student" name="student" value="{{$student->first_name}} {{$student->last_name}}" readonly>
    </div>

    <div class="mb-3">
        <label for="subjects" class="form-label">Stream Subjects</label>
        <div class="row">
            @foreach ($subjects as $subject)
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$subject->id}}" name="subjects[]"
                        id="{{$subject->code}}"
                        @if(in_array($subject->id, $assignedSubjectIds)) checked @endif>
                    <label class="form-check-label" for="{{$subject->code}}">
                        {{$subject->name}}
                    </label>
                </div>
                <x-form-error name="subjects" />
            </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Change</button>
        <button type="reset" class="btn btn-outline-secondary">Clear</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Assign Subjects to Students | Student Management System');
    });
</script>
@endsection