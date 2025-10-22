@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-black mb-4">📝 Activity Logs</h2>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4 d-flex gap-2">
        <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control w-25"
               placeholder="Search by action, model, or description">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    {{-- Table --}}
    <div class="card bg-light text-dark shadow-lg rounded-4">
        <div class="card-body">
            <table class="table table-striped align-middle text-dark">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Description</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td>{{ $log->user?->name ?? 'System' }}</td>
                            <td>{{ $log->action }}</td>
                            <td>{{ $log->model }}</td>
                            <td>{{ $log->description ?? '—' }}</td>
                            <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No activity logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $logs->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
