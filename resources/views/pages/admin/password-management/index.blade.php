@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold mb-4">🔐 Password Management</h2>

    {{-- ✅ Success Message --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    {{-- ✅ Search Bar --}}
    <form method="GET" action="{{ route('admin.password.manage') }}"
          class="mb-4 d-flex justify-content-center">
        <div class="input-group shadow-sm" style="max-width: 500px;">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   class="form-control border-0"
                   placeholder="🔍 Search by User ID, Name, or Role">
            <button class="btn btn-gradient-primary text-white px-4" type="submit">
                Search
            </button>
        </div>
    </form>

    {{-- ✅ Users Table Card --}}
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header bg-gradient-dark text-white fw-bold py-3">
            <i class="fas fa-users-cog me-2"></i> User Accounts
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->user_id }}</td>
                            <td>
                                @if($user->role_id == 1)
                                    {{ $user->user_id }}
                                @elseif($user->role_id == 2 && $user->student)
                                    {{ $user->student->first_name }} {{ $user->student->last_name }}
                                @elseif($user->role_id == 3 && $user->teacher)
                                    {{ $user->teacher->salutation }} {{ $user->teacher->first_name }} {{ $user->teacher->last_name }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if($user->role_id == 1)
                                    <span class="badge bg-danger px-3 py-2">Admin</span>
                                @elseif($user->role_id == 2)
                                    <span class="badge bg-success px-3 py-2">Student</span>
                                @elseif($user->role_id == 3)
                                    <span class="badge bg-info text-dark px-3 py-2">Teacher</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Unknown</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{-- Reset Password --}}
                                <a href="{{ route('admin.password.form', $user->id) }}"
                                   class="btn btn-sm btn-warning rounded-pill shadow-sm">
                                    <i class="fas fa-key me-1"></i> Reset Password
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ✅ Pagination --}}
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
</div>

{{-- ✅ Custom Styles --}}
<style>
    .bg-gradient-dark {
        background: linear-gradient(135deg, #343a40, #495057);
    }
    .btn-gradient-primary {
        background: linear-gradient(45deg, #007bff, #6f42c1);
        border: none;
        transition: 0.3s ease;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
</style>
@endsection
