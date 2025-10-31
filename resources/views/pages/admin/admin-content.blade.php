<x-private-layout>

    <!-- Sidebar -->
    <div class="d-flex">
        <nav id="sidebar" class="bg-dark text-white vh-100 p-3" style="width: 240px; position: fixed;">
            <div class="mb-4 d-flex align-items-center">
                <img src="{{ asset('path/to/logo.png') }}" alt="Logo" class="me-2" width="40">
                <h4 class="m-0">Acadtrack</h4>
            </div>

            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.documents.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.enrollment.index') }}">
                        <i class="fa-solid fa-user-graduate me-2"></i> Students
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.promotion-history.index') }}">
                        <i class="fa-solid fa-clock-rotate-left me-2"></i> History Logs
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="/admin/profile">
                        <i class="fa fa-user me-2"></i> Profile
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <a class="btn btn-danger w-100" href="/logout">
                        <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout
                    </a>
                </li>
            </ul>

            <hr class="text-secondary">
            <p class="text-center small mb-0">Logged in as: <strong>{{ auth()->user()->role }}</strong></p>
        </nav>

        <!-- Main Content -->
        <div id="layoutSidenav_content" class="flex-grow-1" style="margin-left: 240px;">
            <x-nav-top></x-nav-top>
            <div class="container-fluid p-4">
                @yield('content')
            </div>
        </div>
    </div>

</x-private-layout>
