<x-private-layout>

    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role }}">

        <div class="nav">
            <!-- Dashboard now points to Documents Dashboard -->
<a class="nav-link" href="{{ route('admin.documents.dashboard') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
    Dashboard
</a>

              <a class="nav-link" href="{{ route('admin.enrollment.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                Students
            </a>



                {{-- <x-sub-nav-link href="/admin/students/create">Add</x-sub-nav-link> --}}
                {{-- <x-sub-nav-link href="/admin/students/show">View</x-sub-nav-link> --}}
                {{-- <x-sub-nav-link href="{{ route('admin.students.assign.form') }}">Assign</x-sub-nav-link> --}}
            {{-- </x-nav-link> --}}

            {{-- <x-nav-link idNumber="2" link_name="Teachers" icon_class="fa-solid fa-chalkboard-user">
                <x-sub-nav-link href="/admin/teachers/create">Add</x-sub-nav-link>
                <x-sub-nav-link href="/admin/teachers/show">View</x-sub-nav-link>
            </x-nav-link> --}}

          {{-- <x-nav-link idNumber="3" link_name="Subjects" icon_class="fa-solid fa-book"> --}}
    {{-- <x-sub-nav-link href="/admin/subjects/create">Add</x-sub-nav-link> --}}
    {{-- <x-sub-nav-link href="/admin/subjects/show">View</x-sub-nav-link>
    <x-sub-nav-link href="/admin/subjects/assign">Assign Teachers</x-sub-nav-link>
</x-nav-link> --}}

{{--
            <x-nav-link idNumber="5" link_name="Classes" icon_class="fa-solid fa-chalkboard">
                <x-sub-nav-link href="/admin/class/create">Add</x-sub-nav-link>
                <x-sub-nav-link href="/admin/class/show">View</x-sub-nav-link>
            </x-nav-link> --}}
            {{-- ✅ New: Promotion History Logs --}}
            <a class="nav-link" href="{{ route('admin.promotion-history.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            History logs
            </a>

            <a class="nav-link" href="/admin/profile">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Profile
            </a>
            {{-- <a class="nav-link" href="{{ route('admin.password.manage') }}">
           <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>
            Password Management
           </a> --}}
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
            @yield('content')
            <!--  -->
        </div>
    </div>

</x-private-layout>
