<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code | Acadtrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .btn-success {
            background-color: #2ed573;
            border: none;
        }
        .btn-success:hover {
            background-color: #28a745;
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
            <h4 class="text-center mb-3 text-dark fw-bold">Verify Code</h4>
            <p class="text-muted text-center mb-4">Enter the 6-digit code sent to your registered email.</p>

            {{-- ✅ Session Alerts --}}
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="alert alert-success text-center">{{ session('success') }}</div>
            @endif

            {{-- ✅ Verification Form --}}
            <form action="{{ route('forgotPassword.verifyCode') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">

                <div class="mb-3">
                    <label class="form-label fw-bold">Registered Email</label>
                    <input type="email" class="form-control text-center" value="{{ session('email') }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Verification Code</label>
                    <input type="text"
                           name="code"
                           class="form-control text-center"
                           maxlength="6"
                           placeholder="Enter 6-digit code"
                           required
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                </div>

                <button type="submit" class="btn btn-success w-100 fw-semibold">
                    Verify Code
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('forgotPassword.form') }}">← Back to Forgot Password</a>
            </div>
        </div>
    </div>
</body>
</html>
