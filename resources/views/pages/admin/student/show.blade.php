<x-private-layout>
    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role->name }}">
        <div class="nav">
            <a class="nav-link" href="/admin/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <x-nav-link idNumber="1" link_name="Students" icon_class="fa-solid fa-user-graduate">
                <x-sub-nav-link href="/admin/students/show">View</x-sub-nav-link>
                <x-sub-nav-link href="/admin/students/create">Add</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="2" link_name="Teachers" icon_class="fa-solid fa-chalkboard-user">
                <x-sub-nav-link href="/admin/teachers/show">View</x-sub-nav-link>
                <x-sub-nav-link href="/admin/teachers/create">Add</x-sub-nav-link>
            </x-nav-link>



            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="./teacher-profile.php">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Profile
            </a>
            <a class="nav-link getPopup" href="./settings.php">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                Settings
            </a>
            <a class="nav-link getPopup" href="/logout">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                Logout
            </a>
        </div>
    </x-navbar>
    <x-nav-top></x-nav-top>
    <div id="layoutSidenav_content">
        <div class="container-fluid mt-2">
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
                                    <a href="/admin/students/{{$student->id}}/edit" class="btn btn-warning">Edit</a>
                                    <form action="/admin/students/{{$student->id}}" id="delete-student">
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
                        <div class="col-md-6">
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
                    </div>
                </div>
            </div>
            <!--  -->
        </div>
    </div>
    <!--  -->

</x-private-layout>