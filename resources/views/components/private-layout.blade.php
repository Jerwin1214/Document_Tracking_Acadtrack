<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('build/css/navbar.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body class="sb-nav-fixed">

<div id="layoutSidenav">
    {{-- Sidebar --}}
    <x-navbar role="{{ auth()->user()->role }}">
        {{ $slot->isEmpty() ? '' : $slot }}
    </x-navbar>

    {{-- Main Content --}}
    <div id="layoutSidenav_content">
        <x-nav-top></x-nav-top>

        <div class="container-fluid mt-2">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="{{ asset('build/js/navbar.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
