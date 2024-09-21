@extends('pages.admin.admin-content')

@section('content')
<h2>Assign Subjects to Teachers</h2>

<form action="/admin/subjects/assign" method="post" class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    @csrf
    <div class="mb-3">
        <label for="teachers" class="form-label">Teacher</label>
        <select name="teacher" id="teachers" class="form-select">
            <option value="">-- Choose One --</option>
            @foreach ($teachers as $teacher)
            <option value="{{$teacher->id}}">{{$teacher->salutation}} {{$teacher->first_name}} {{$teacher->last_name}}</option>
            @endforeach
        </select>
        <x-form-error name="teacher" />
    </div>

    <div class="mb-3">
        <label for="subjects" class="form-label">Subjects</label>
        <div class="row">
            @foreach ($subjects as $subject)
            <div class="col-sm-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$subject->id}}" name="subjects[]" id="{{$subject->code}}">
                    <label class="form-check-label" for="{{$subject->code}}">
                        {{$subject->code}}
                    </label>
                </div>
                <x-form-error name="subjects" />
            </div>
            @endforeach
        </div>
    </div>

    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Assign</button>
        <button type="reset" class="btn btn-outline-secondary">Clear</button>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#teachers").change(function() {
            const teacherId = $(this).val();
            $.ajax({
                url: `/admin/subjects/teachers/${teacherId}`,
                type: 'GET',
                data: {
                    id: teacherId,
                },
                success: function(response) {
                    response.forEach(subject => {
                        $(`input[value=${subject.id}]`).prop('checked', true);
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>


@endsection