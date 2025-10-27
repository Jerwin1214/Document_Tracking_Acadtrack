<x-private-layout>

    <!-- Navbar -->
    <x-navbar role="{{ auth()->user()->role }}">

        <div class="nav">
            <!-- Dashboard now points to Documents Dashboard -->
<a class="nav-link" href="{{ route('admin.documents.dashboard') }}">
    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
    Dashboard
</a>
{{--
              <a class="nav-link" href="{{ route('admin.enrollment.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                Students
            </a> --}}

            <a class="nav-link" href="{{ route('admin.promotion-history.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
            History logs
            </a>

            <a class="nav-link" href="/admin/profile">
                <div class="sb-nav-link-icon"><i class="fa fa-user" aria-hidden="true"></i></div>
                Profile
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
            @yield('content')
            <!--  -->
        </div>
    </div>

</x-private-layout>
