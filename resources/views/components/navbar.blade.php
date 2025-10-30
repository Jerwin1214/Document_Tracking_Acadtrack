@props(['role' => ''])

<div id="layoutSidenav_nav">
    <nav class="sb-sidenav sb-sidenav-dark">

        <div class="sb-sidenav-menu">
            {{-- Load the sidebar links here instead of using $slot --}}
            <x-sidebar-links />
        </div>

        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <strong title="Your Role">{{ $role ?: (auth()->user()->role ?? 'User') }}</strong>
        </div>
    </nav>
</div>
