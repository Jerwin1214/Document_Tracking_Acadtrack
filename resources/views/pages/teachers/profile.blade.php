@extends('pages.teachers.teacher-content')

@section('content')
<h2>Profile</h2>
<div class="shadow-lg p-3 mb-5 mt-3 bg-body-tertiary rounded">
    <div class="container row">
        <div class="col-md-4">
            <div class="card text-center" style="width: 14rem;">
                <div class="">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" class="card-img-top" alt="Profile Image" style="border-radius: 50%;">
                </div>
                <div class="card-body">
                    <h5 class="card-title">Admin</h5>
                    <a href="/admin/settings" class="btn btn-outline-warning">Settings</a>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" value="{{$teacher->first_name}} {{$teacher->last_name}}" name="name" id="name" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" value="{{auth()->user()->email}}" name="email" id="email" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="date_created" class="form-label">Created at</label>
                <input type="text" value="{{auth()->user()->created_at}}" name="date_created" id="date_created" class="form-control" readonly>
            </div>
            <div class="mb-3">
                <label for="date_updated" class="form-label">Updated at</label>
                <input type="text" value="{{auth()->user()->updated_at}}" name="date_updated" id="date_updated" class="form-control" readonly>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Profile | Student Management System');
    });
</script>
@endsection
