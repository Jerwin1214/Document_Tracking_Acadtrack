@extends('pages.admin.admin-content')

@section('content')
<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<h2 class="mb-4">Change Password</h2>

<div class="shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="max-width: 500px; margin:auto;">
    <form id="changePasswordForm" method="POST" action="{{ route('admin.change-password') }}">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div class="mb-3 position-relative">
            <label for="old_password" class="form-label">Current Password</label>
            <div class="input-group">
                <input type="password" name="old_password" id="old_password" class="form-control" placeholder="Enter current password" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('old_password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <!-- New Password -->
        <div class="mb-3 position-relative">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <small id="passwordHelp" class="text-danger d-none">Password must be at least 8 characters.</small>
        </div>

        <!-- Confirm New Password -->
        <div class="mb-3 position-relative">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password" required>
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password_confirmation', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            <small id="confirmHelp" class="text-danger d-none">Passwords do not match.</small>
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-success">Update Password</button>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.title = 'Change Password | Student Management System';

    // SweetAlert for success
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session("success") }}',
        timer: 2500,
        showConfirmButton: false
    });
    @endif

    // SweetAlert for error (old password incorrect or validation errors)
    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session("error") }}',
        timer: 3000,
        showConfirmButton: true
    });
    @endif

    @if($errors->any())
    let errors = '';
    @foreach ($errors->all() as $error)
        errors += '{{ $error }}\n';
    @endforeach
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: errors,
        showConfirmButton: true
    });
    @endif
});
</script>
@endsection
