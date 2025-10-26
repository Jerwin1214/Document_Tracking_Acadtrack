<x-private-layout>
    <!-- Top Navbar -->
    <x-navbar role="{{ auth()->user()->role }}" />

    <div id="layoutSidenav" class="d-flex">
        <!-- Sidebar -->
        <x-sidebar role="{{ auth()->user()->role }}">
            <a class="nav-link" href="{{ route('admin.documents.dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>

            <a class="nav-link" href="{{ route('admin.enrollment.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-graduate"></i></div>
                Students
            </a>

            <a class="nav-link" href="{{ route('admin.promotion-history.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                History Logs
            </a>

            <a class="nav-link" href="/admin/profile">
                <div class="sb-nav-link-icon"><i class="fa fa-user"></i></div>
                Profile
            </a>

            <a class="nav-link getPopup" href="/logout">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                Logout
            </a>
        </x-sidebar>

        <!-- Main Content -->
        <div id="layoutSidenav_content" class="flex-fill p-4">
            <div class="container-fluid mt-2">
                @yield('content')
            </div>
        </div>
    </div>
</x-private-layout>
