<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Acadtrack</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
  <!-- your custom sb layout css -->
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <style>
    /* small runtime safety: ensure body bg and smooth */
    html,body{height:100%;}
    body{background:#121318;color:#e7e7ee;}
  </style>
</head>

<body class="sb-nav-fixed">
  <!-- Top navbar (keeps your x-nav-top component) -->
  <x-nav-top />

  <!-- Layout wrapper required by JS/CSS -->
  <div id="layoutSidenav">
    <!-- Sidebar nav -->
    <div id="layoutSidenav_nav">
      <nav class="sb-sidenav sb-sidenav-dark">
        <div class="sb-sidenav-menu">
          {{ $slot ?? '' }}
        </div>

        <div class="sb-sidenav-footer">
          <div class="small">Logged in as:</div>
          <strong title="Your Role">{{ auth()->user()->role ?? 'user' }}</strong>
        </div>
      </nav>
    </div>

    <!-- Main content -->
    <div id="layoutSidenav_content">
      <main class="pt-3">
        <div class="container-fluid">
          @yield('content')
        </div>
      </main>
      <!-- optional footer -->
      <footer class="py-3 mt-auto">
        <div class="container-fluid text-muted small text-center">© {{ date('Y') }} Acadtrack</div>
      </footer>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="{{ asset('js/navbar.js') }}"></script>
</body>
</html>
