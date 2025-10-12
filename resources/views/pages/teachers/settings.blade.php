@extends('pages.teachers.teacher-content')

@section('content')
<h2 class="mb-4">Change Password</h2>

{{-- Popup messages --}}
@if (session('success'))
<script>
    Swal.fire({
        icon: "success",
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 1500
    });
</script>
@endif

@if (session('error'))
<script>
    Swal.fire({
        icon: "error",
        title: "{{ session('error') }}",
        showConfirmButton: true,
    });
</script>
@endif

<div class="shadow-lg p-4 mb-5 mt-3 bg-body-tertiary rounded mx-auto" style="max-width: 480px;">
    <form action="{{ route('teacher.updatePassword') }}" method="post">
        @csrf

        {{-- Old Password --}}
        <div class="mb-3">
            <label for="old_password" class="form-label">Old Password</label>
            <div class="input-group">
                <input type="password" name="old_password" id="old_password" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="old_password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-form-error name="old_password" />
        </div>

        {{-- New Password --}}
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-form-error name="password" />
        </div>

        {{-- Confirm New Password --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm New Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password_confirmation">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            <x-form-error name="password_confirmation" />
        </div>

        {{-- Buttons --}}
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Change Password</button>
            <button type="reset" class="btn btn-outline-secondary">Clear</button>
        </div>
    </form>
</div>

{{-- Page title and toggle script --}}
<script>
    document.title = "Change Password | Teacher Dashboard";

    // Toggle password visibility
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const type = target.type === 'password' ? 'text' : 'password';
            target.type = type;
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });
</script>
@endsection
