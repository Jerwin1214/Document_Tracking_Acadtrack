<x-private-layout>

    <style>
        /* Sidebar Styling */
        #sidebar {
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #212529;
            color: white;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        #sidebar.collapsed {
            margin-left: -240px;
        }

        #sidebar .nav-link {
            color: #adb5bd;
            transition: all 0.2s ease;
        }

        #sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        #sidebar h4 {
            font-size: 1.25rem;
        }

        /* Main content shift */
        #layoutSidenav_content {
            transition: all 0.3s ease;
            margin-left: 240px;
        }

        #layoutSidenav_content.expanded {
            margin-left: 0;
        }

        /* Mobile responsiveness */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: -240px;
            }

            #sidebar.show {
                margin-left: 0;
            }

            #layoutSidenav_content {
                margin-left: 0 !important;
            }
        }

        /* Overlay for mobile */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1040;
        }

        #overlay.show {
            display: block;
        }
    </style>

    <!-- Top Navbar -->
    <nav class="navbar navbar-dark bg-dark fixed-top d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center">
            <!-- Sidebar Toggle -->
            <button class="btn btn-outline-light me-2" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.documents.dashboard') }}">
                <img src="{{ asset('images/acadtracklogo.jpg') }}" alt="Logo"
                     class="rounded-circle me-2"
                     style="height: 30px; width: 30px; object-fit: cover;">
                <span>Acadtrack</span>
            </a>
        </div>

        <div class="text-white small">
            Logged in as: <strong>{{ auth()->user()->role }}</strong>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="d-flex">
        <nav id="sidebar" class="p-3">
            <div class="mb-4 d-flex align-items-center">
                <img src="{{ asset('images/acadtracklogo.jpg') }}" alt="Logo" class="me-2 rounded-circle" width="40">
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
        </nav>

        <!-- Main Content -->
        <div id="layoutSidenav_content" class="flex-grow-1">
            <div class="container-fluid mt-5 pt-3 p-4">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Overlay for mobile -->
    <div id="overlay"></div>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebar = document.getElementById("sidebar");
            const toggleButton = document.getElementById("sidebarToggle");
            const mainContent = document.getElementById("layoutSidenav_content");
            const overlay = document.getElementById("overlay");

            // Toggle sidebar
            toggleButton.addEventListener("click", function () {
                if (window.innerWidth < 992) {
                    sidebar.classList.toggle("show");
                    overlay.classList.toggle("show");
                } else {
                    sidebar.classList.toggle("collapsed");
                    mainContent.classList.toggle("expanded");
                }
            });

            // Hide sidebar when clicking outside (mobile only)
            overlay.addEventListener("click", function () {
                sidebar.classList.remove("show");
                overlay.classList.remove("show");
            });

            // Automatically close on resize
            window.addEventListener("resize", function () {
                if (window.innerWidth >= 992) {
                    overlay.classList.remove("show");
                    sidebar.classList.remove("show");
                }
            });
        });
    </script>

</x-private-layout>
