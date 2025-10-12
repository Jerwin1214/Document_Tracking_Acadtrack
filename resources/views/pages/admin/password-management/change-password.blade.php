@extends('pages.admin.admin-content')

@section('content')
<div class="container mt-5" style="max-width: 600px;">
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h2 class="mb-4 text-center fw-bold">🔐 Reset Password</h2>
            <p class="text-center text-muted mb-4">Updating password for <strong>{{ $userName }}</strong></p>

            {{-- SweetAlert for validation errors --}}
            @if($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: `
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        `,
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif

            {{-- SweetAlert for success --}}
            @if(session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif

            {{-- SweetAlert for other errors --}}
            @if(session('swal_error'))
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops!',
                        text: '{{ session('swal_error') }}',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif

            <form method="POST" action="{{ route('admin.password.update', $user->id) }}">
                @csrf

                {{-- New Password --}}
                <div class="mb-3 position-relative">
                    <label class="form-label fw-semibold">New Password</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="passwordField" required>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                          style="cursor:pointer;"
                          onclick="togglePassword('passwordField', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3 position-relative">
                    <label class="form-label fw-semibold">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-control @error('password_confirmation') is-invalid @enderror"
                           id="confirmPasswordField" required>
                    <span class="position-absolute top-50 end-0 translate-middle-y me-3"
                          style="cursor:pointer;"
                          onclick="togglePassword('confirmPasswordField', this)">
                        <i class="bi bi-eye"></i>
                    </span>
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-success fw-bold px-4">Update Password</button>
                    <a href="{{ route('admin.password.manage') }}" class="btn btn-secondary fw-bold px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePassword(fieldId, el) {
        const field = document.getElementById(fieldId);
        const icon = el.querySelector("i");
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        }
    }
</script>
@endpush
