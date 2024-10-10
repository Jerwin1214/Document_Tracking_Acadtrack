@extends('pages.admin.admin-content')

@section('content')
<h2>Assign Classes</h2>

<form action="/admin/teachers/{{$teacher->id}}/assign-class" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label for="teacher" class="form-label">Teacher</label>
        <input type="text" name="teacher" id="teacher" class="form-control" value="{{ $teacher->first_name }} {{ $teacher->first_name }}" readonly>
        <x-form-error name="teacher" />
    </div>
    <div class="mb-3">
        <label for="class" class="form-label">Classes</label>
        <div class="row">
            @foreach ($classes as $class)
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$class->id}}" name="classes[]"
                        id="{{$class->name}}">
                    <label class="form-check-label" for="{{$class->name}}">
                        {{$class->name}}
                    </label>
                </div>
                <x-form-error name="classes" />
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