<x-private-layout>
    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role->name }}">
        <div class="nav">
            <a class="nav-link" href="/admin/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <!-- alrert listeners -->
            @if (session('success'))
            <x-alert-message icon="success" message="{{session('success')}}" />
            @endif

            @if (session('warning'))
            <x-alert-message icon="warning" message="{{session('warning')}}" />
            @endif

            @if (session(key: 'error'))
            <x-alert-message icon="error" message="{{session('error')}}" />
            @endif
            <!--  -->

            <x-nav-link idNumber="1" link_name="Students" icon_class="fa-solid fa-user-graduate">
                <x-sub-nav-link href="/admin/students/show">View</x-sub-nav-link>
                <x-sub-nav-link href="/admin/students/create">Add</x-sub-nav-link>
            </x-nav-link>

            <x-nav-link idNumber="2" link_name="Teachers" icon_class="fa-solid fa-chalkboard-user">
                <x-sub-nav-link href="./view-playlists.php">View</x-sub-nav-link>
                <x-sub-nav-link href="./add-playlist.php">Add</x-sub-nav-link>
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
            <h2>All Students</h2>
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 1;
                    @endphp

                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                        <td>{{ $student->user->email }}</td>
                        <td>
                            <a href="/admin/students/{{ $student->id }}" class="btn btn-primary btn-sm">View</a>
                            <a href="/admin/students/{{ $student->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                            <form action="/admin/students/{{ $student->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                    @endforeach

                </tbody>
            </table>
            <!--  -->
        </div>
    </div>
    <!--  -->

</x-private-layout>