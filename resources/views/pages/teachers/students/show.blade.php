@extends('pages.teachers.teacher-content')

@section('content')
<!-- Slotted content -->
<h2>{{$student->first_name}}'s Profile</h2>
<div class="container py-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                        alt="avatar" class="rounded-circle img-fluid" style="width: 150px;">
                    <h5 class="my-2">{{$student->first_name}} {{$student->last_name}}</h5>
                    <div class="d-flex justify-content-center mb-2">
                        <a href="/teacher/students/{{$student->id}}/edit" class="btn btn-warning">Edit</a>
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
                            <p class="text-muted mb-0">{{$student->first_name}} {{$student->last_name}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Gender</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$student->gender}} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">Email</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$student->user->email}} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">DOB</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$student->dob}} </p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <p class="mb-0">NIC</p>
                        </div>
                        <div class="col-sm-9">
                            <p class="text-muted mb-0"> {{$student->nic}} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if($student->guardian)
            <div class="col-md-4">
                <div class="card mb-4 mb-md-0">
                    <div class="card-body">
                        <p class="mb-4">Guardian Details</p>
                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"> {{$student->guardian->initials}} {{$student->guardian->first_name}} {{$student->guardian->last_name}}</p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone No.</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"> {{$student->guardian->phone_number}} </p>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-sm-3">
                                <p class="mb-0">NIC</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0"> {{$student->guardian->nic}} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-8">
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
                                @foreach ($subjects as $subject)
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
        $(document).prop('title', 'Student Profile | Student Management System');
    });
</script>

@endsection