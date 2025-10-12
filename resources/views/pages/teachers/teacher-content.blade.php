<x-private-layout>
    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role }}">
        <div class="nav">
            <!-- Dashboard -->
            <a class="nav-link" href="{{ route('teacher.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <!-- Students -->
            <x-nav-link idNumber="1" link_name="Students" icon_class="fa-solid fa-user-graduate">
                <x-sub-nav-link href="{{ route('teacher.students.index') }}">View</x-sub-nav-link>
            </x-nav-link>

<!-- ✅ Grades -->
<x-nav-link idNumber="3" link_name="Grades" icon_class="fa-solid fa-book-open">
    <x-sub-nav-link href="{{ route('teacher.grades.index') }}">Manage</x-sub-nav-link>
    <x-sub-nav-link href="{{ route('teacher.grades.view.select-class') }}">View</x-sub-nav-link>
</x-nav-link>


            <!-- Announcements -->
            <x-nav-link idNumber="2" link_name="Announcements" icon_class="fa-solid fa-bullhorn">
                <x-sub-nav-link href="{{ route('teacher.announcements.create') }}">Post</x-sub-nav-link>
                <x-sub-nav-link href="{{ route('teacher.announcements.index') }}">View</x-sub-nav-link>
            </x-nav-link>

            <!-- Addons -->
            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link" href="{{ route('teacher.profile') }}">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Profile
            </a>
            <a class="nav-link getPopup" href="{{ route('teacher.settings') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
                Forget Password
            </a>
            <a class="nav-link getPopup" href="{{ route('logout') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                Logout
            </a>
        </div>
    </x-navbar>

    <x-nav-top></x-nav-top>

    <div id="layoutSidenav_content">
        <div class="container-fluid">
            <!-- Slotted content -->
            @yield('content')
        </div>
    </div>
</x-private-layout>
