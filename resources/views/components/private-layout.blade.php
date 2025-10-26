<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acadtrack Dashboard</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />

    <!-- Custom Navbar CSS -->
    <link rel="stylesheet" href="{{ asset('build/css/navbar.css') }}">

    <!-- SweetAlert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body class="sb-nav-fixed">

    <!-- Top Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand d-flex align-items-center ps-3" href="{{ route('admin.documents.dashboard') }}">
            <img src="{{ asset('images/acadtracklogo.jpg') }}" alt="Logo" class="rounded-circle me-2"
                 style="height:30px; width:30px; object-fit:cover;">
            <span>Acadtrack</span>
        </a>

        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <!-- Layout Sidenav -->
    <div id="layoutSidenav" class="d-flex">

        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
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
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    <b>{{ auth()->user()->role ?? 'admin' }}</b>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content" class="flex-fill p-4">
            <div class="container-fluid mt-2">
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('build/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('layoutSidenav')?.classList.toggle('sb-sidenav-toggled');
        });
    </script>

    <!-- Optional: Add smooth transition for sidebar -->
    <style>
        #layoutSidenav_nav {
            min-width: 250px;
            transition: all 0.3s;
        }

        #layoutSidenav.sb-sidenav-toggled #layoutSidenav_nav {
            margin-left: -250px;
        }

        #layoutSidenav_content {
            transition: margin-left 0.3s;
        }

        #layoutSidenav.sb-sidenav-toggled #layoutSidenav_content {
            margin-left: 0;
        }
    </style>
</body>

</html>
