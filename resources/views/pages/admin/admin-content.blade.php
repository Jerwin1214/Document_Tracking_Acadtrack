<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/navbar.css') }}">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body class="sb-nav-fixed">

    <!-- Top Navbar -->
    <x-nav-top></x-nav-top>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <x-navbar role="{{ auth()->user()->role }}">
            <div class="nav flex-column">
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
            </div>
        </x-navbar>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid mt-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('build/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        // Sidebar toggle
        $("#sidebarToggle").on("click", function (e) {
            e.preventDefault();
            $("body").toggleClass("sb-sidenav-toggled");
        });
    </script>
</body>

</html>
