@extends('pages.admin.admin-content')

@section('content')
<!-- Slotted content -->
<h2>{{$teacher->initials}} {{$teacher->last_name ?? $teacher->first_name}}'s Profile</h2>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <img src={{$teacher->salutation == 'Mr.' ||$teacher->salutation == 'Dr.'  ? "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" : "https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava2.webp"}}
                        alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-2">{{$teacher->first_name}} {{$teacher->last_name}}</h5>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="/admin/teachers/{{$teacher->id}}/edit" class="btn btn-warning">Edit</a>
                        <form action="/admin/teachers/{{$teacher->id}}" id="delete-teacher">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger ms-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Full Name</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0">{{$teacher->initials}} {{$teacher->first_name}} {{$teacher->last_name}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$teacher->user->email}} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">DOB</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$teacher->dob}} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">NIC</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$teacher->nic}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-4 mb-md-0">
                    <div class="card-body">
                        <p class="mb-4">Assigned Subjects</p>
                        <table class="table table-responsive">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacher->subjects as $subject)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$subject->code}}</td>
                                    <td>{{$subject->name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--  -->

<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'Teacher Profile | Student Management System');
    });
</script>

@endsection