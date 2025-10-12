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

            <!-- Change Password Button -->
            <a href="{{ route('admin.password') }}" class="btn btn-gradient-primary px-4 rounded-pill shadow-sm">
                <i class="fas fa-lock me-2"></i> Change Password
            </a>
        </div>
    </div>
</div>

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
