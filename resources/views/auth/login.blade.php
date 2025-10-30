<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Acadtrack Digital Document Tracking System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: url('{{ asset('images/BGpicture.png') }}') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .login-card img {
            width: 90px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .login-card h4 {
            font-weight: bold;
            color: #295c3b;
            margin-bottom: 30px;
            text-transform: uppercase;
        }
        .form-control {
            border-radius: 25px;
            padding-left: 40px;
        }
        .input-group-text {
            border: none;
            background: transparent;
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }
        .input-group {
            position: relative;
            margin-bottom: 10px;
        }
        .btn-login {
            width: 100%;
            border-radius: 25px;
            background-color: #295c3b;
            color: #fff;
        }
        .btn-login:hover {
            background-color: #1e4a30;
        }
        .forgot-link {
            display: block;
            margin-top: 10px;
            font-size: 0.9rem;
            text-decoration: none;
            color: #295c3b;
        }
        .forgot-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

@php
    $role = session('user_role'); // 'admin', 'teacher', 'student'
@endphp

@if (!$role)
    <script> window.location.href = "{{ route('select.role') }}"; </script>
@endif

<div class="login-card">
    <img src="{{ asset('logo.jpg') }}" alt="School Logo">
    <h4>{{ strtoupper($role) }}</h4>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
            <input type="text" name="user_id"
                   class="form-control @error('user_id') is-invalid @enderror"
                   placeholder="User ID"
                   required
                   @if($role === 'student')
                        pattern="^\d{4}-\d{4}$"
                        title="Format: YYYY-NNNN"
                   @endif
            >
        </div>
        @error('user_id')
        <div class="text-danger text-start small mb-2">{{ $message }}</div>
        @enderror

        <div class="input-group">
            <span class="input-group-text"><i class="fas fa-lock"></i></span>
            <input type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password"
                   required
            >
        </div>
        @error('password')
        <div class="text-danger text-start small mb-2">{{ $message }}</div>
        @enderror

        @if (session('error'))
            <div class="text-danger text-center small mb-2">{{ session('error') }}</div>
        @endif

        <button type="submit" class="btn btn-login mt-3">LOG IN</button>
        <a href="{{ route('forgotPassword.form') }}" class="forgot-link">Forgot password?</a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if (session('greeting'))
<script>
Swal.fire({
    icon: 'success',
    title: '{{ session('greeting') }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

@if ($errors->any() && !session('error'))
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: '{{ $errors->first() }}',
    showConfirmButton: true
});
</script>
@endif

</body>
</html>
