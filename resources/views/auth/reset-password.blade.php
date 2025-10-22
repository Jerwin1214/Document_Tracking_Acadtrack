<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body style="background-color:#f8f9fa;">

    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow p-4" style="width: 400px; border-radius: 15px;">
            <h4 class="text-center mb-3 fw-bold text-primary">Reset Password</h4>
            <p class="text-muted text-center mb-4">Enter your new password below.</p>

            {{-- ✅ Session Alerts --}}
            @if (session('error'))
                <div class="alert alert-danger text-center">{{ session('error') }}</div>
            @endif

            {{-- ✅ Reset Password Form --}}
            <form action="{{ route('forgotPassword.reset') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" class="form-control" name="password"
                           placeholder="Enter new password" required minlength="6">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm Password</label>
                    <input type="password" class="form-control" name="password_confirmation"
                           placeholder="Confirm your password" required minlength="6">
                </div>

                <button type="submit" class="btn btn-success w-100 fw-semibold">Reset Password</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none">← Back to Login</a>
            </div>
        </div>
    </div>

    {{-- ✅ SweetAlert Success --}}
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session("success") }}',
            confirmButtonColor: '#28a745',
        });
    </script>
    @endif

</body>
</html>
