@props(['role' => ''])

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav sb-sidenav-dark">
        <div class="sb-sidenav-menu">
            {{ $slot }}
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <strong title="Your Role">{{ $role }}</strong>
        </div>
    </nav>
</div>
