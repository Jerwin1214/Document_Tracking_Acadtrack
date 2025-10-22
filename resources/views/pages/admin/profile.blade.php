@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">👤 Admin Profile</h2>

    <!-- Profile Card -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden mx-auto" style="max-width: 600px;">
        <!-- Gradient Header -->
        <div class="bg-gradient-primary text-white text-center py-5">
            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp"
                 alt="Profile Image"
                 class="rounded-circle border border-3 border-white shadow"
                 style="width:130px; height:130px; object-fit:cover;">
            <h4 class="mt-3 mb-0">{{ auth()->user()?->name ?? 'Admin' }}</h4>
            <p class="text-white-50 mb-0">System Administrator</p>
        </div>

        <!-- Profile Info -->
        <div class="card-body text-center p-4">
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="p-3 bg-light rounded shadow-sm h-100 hover-card">
                        <h6 class="text-muted mb-1">Created At</h6>
                        <p class="fw-semibold mb-0">
                            {{ auth()->user()?->created_at?->format('F d, Y h:i A') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded shadow-sm h-100 hover-card">
                        <h6 class="text-muted mb-1">Last Updated</h6>
                        <p class="fw-semibold mb-0">
                            {{ auth()->user()?->updated_at?->format('F d, Y h:i A') ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- ✅ Display Email -->
            <div class="mb-3 text-start">
                <label class="form-label fw-semibold">Email Address</label>
                <div class="input-group">
                    <input type="text" class="form-control text-center" value="{{ auth()->user()?->email ?? 'No Email Added' }}" readonly>
                    <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#updateEmailModal">
                        <i class="fas fa-edit me-1"></i> Update
                    </button>
                </div>
            </div>

            <!-- Change Password Button -->
            <a href="{{ route('admin.password') }}" class="btn btn-outline-secondary w-100 rounded-pill mt-3">
                <i class="fas fa-lock me-2"></i> Change Password
            </a>
        </div>
    </div>
</div>

<!-- ✅ Update Email Modal -->
<div class="modal fade" id="updateEmailModal" tabindex="-1" aria-labelledby="updateEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header bg-gradient-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="updateEmailModalLabel"><i class="fas fa-envelope me-2"></i> Update Email</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.profile.updateEmail') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">New Email Address</label>
                        <input type="email" name="email" id="email" class="form-control text-center" value="{{ old('email', auth()->user()?->email) }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-gradient-primary rounded-pill"><i class="fas fa-save me-2"></i> Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 Script --}}
@if(session('success'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000,
        toast: true,
        position: 'top-end'
    });
</script>
@endif

{{-- ✅ Custom Styles --}}
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #6f42c1);
    }
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #6f42c1);
        border: none;
        color: #fff;
        transition: 0.3s ease;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        background: #f8f9fa;
        transform: translateY(-3px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        document.title = 'Admin Profile | Acadtrack System';
    });
</script>
@endpush
@endsection
