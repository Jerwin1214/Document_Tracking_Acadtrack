<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <!-- Navbar Brand -->
    <a class="navbar-brand d-flex align-items-center ps-3" href="{{ route('admin.documents.dashboard') }}">
        <img src="{{ asset('images/acadtracklogo.jpg') }}" alt="Logo"
             class="rounded-circle me-2"
             style="height:30px; width:30px; object-fit:cover;">
        <span>Acadtrack</span>
    </a>

    <!-- Sidebar Toggle Button -->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Optional: Right-aligned items (user role, notifications, etc.) -->
    <ul class="navbar-nav ms-auto me-3">
        <li class="nav-item">
            <span class="navbar-text text-light">
                Logged in as: <strong>{{ $role ?? auth()->user()->role ?? 'Admin' }}</strong>
            </span>
        </li>
    </ul>
</nav>
