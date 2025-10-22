<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Acadtrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2f3542;
            color: #f1f2f6;
            font-family: 'Poppins', sans-serif;
        }
        .card {
            border-radius: 15px;
            background-color: #f8f9fa;
        }
        .form-label {
            color: #2f3542;
        }
        .btn-primary {
            background-color: #1e90ff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0066cc;
        }
        .text-muted {
            color: #57606f !important;
        }
        a {
            color: #1e90ff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 400px;">
            <h4 class="text-center mb-3 text-dark">Forgot Password</h4>
            <p class="text-muted text-center mb-4">
                Enter your User ID and registered Email to receive a verification code.
            </p>

            {{-- ✅ Alert Messages --}}
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- ✅ Forgot Password Form --}}
            <form action="{{ route('forgotPassword.sendCode') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="user_id" class="form-label fw-bold">User ID</label>
                    <input type="text" name="user_id" class="form-control" placeholder="Enter your User ID" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Registered Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Send Verification Code
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}">← Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html>
