@props(['role' => ""])

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav flex-column">

                {{-- Default Sidebar Links --}}
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

                {{-- Extra links passed via slot --}}
                {{ $slot }}

            </div>
        </div>

        {{-- Sidebar Footer --}}
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <b title="Your Role">{{ $role }}</b>
        </div>
    </nav>
</div>
