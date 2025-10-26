@props(['role' => "Admin"])

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <!-- Sidebar Menu -->
        <div class="sb-sidenav-menu">
            <div class="nav flex-column">
                {{ $slot }}
            </div>
        </div>

        <!-- Sidebar Footer (User Role) -->
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <b title="Your Role">{{ $role }}</b>
        </div>
    </nav>
</div>
