@extends('pages.admin.admin-content')

@section('content')
<h2>Assign Students to {{$class->name}} - {{$class->year}} class</h2>
<form action="/admin/class/{{$class->id}}/assign" method="post"
    class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label class="form-label" for="class_name">Class</label>
        <input type="text" class="form-control" name="class_name" id="class_name" value="{{$class->name}}" readonly>
        <x-form-error name="class_name" />
    </div>

    {{-- Class id --}}
    <input type="hidden" name="class_id" value="{{$class->id}}">

    <div class="mb-3">
        <label for="students">Students</label>
        <div class="row mt-2">
            @foreach($students as $student)
            <div class="col-md-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$student->id}}" name="students[]"
                        id="flexCheckDefault{{$student->id}}">
                    <label class="form-check-label" for="flexCheckDefault{{$student->id}}">
                        {{$student->first_name}} {{$student->last_name}}
                    </label>
                </div>
                <x-form-error name="students" />
            </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Assign</button>
        <button type="reset" class="btn btn-outline-secondary">Clear</button>
    </div>
</form>
@endsection