@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- 🧭 Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-black mb-0">📈 Student Promotion History</h2>

        {{-- 📜 Button for History Backlogs --}}
        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-dark shadow-sm">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> View Activity Logs
        </a>
    </div>

    {{-- 🔍 Filters --}}
    <form method="GET" action="{{ route('admin.promotion-history.index') }}" class="mb-4 d-flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control w-auto"
               placeholder="Search by LRN or Name">

        <select name="year" class="form-select w-auto">
            <option value="">All School Years</option>
            @foreach($years as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                    {{ $year }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-filter me-1"></i> Filter
        </button>
        <a href="{{ route('admin.promotion-history.index') }}" class="btn btn-secondary">
            <i class="fa-solid fa-rotate-left me-1"></i> Reset
        </a>
    </form>

    {{-- 🧾 Table --}}
    <div class="card bg-light text-dark shadow-lg rounded-4">
        <div class="card-body">
            <table class="table table-striped align-middle text-dark">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Grade Level</th>
                        <th>School Year</th>
                        <th>Status</th>
                        <th>Promoted At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $index => $promo)
                        <tr>
                            <td>{{ $promotions->firstItem() + $index }}</td>
                            <td>{{ $promo->lrn }}</td>
                            <td>{{ $promo->last_name }}, {{ $promo->first_name }} {{ $promo->middle_name }}</td>
                            <td>{{ $promo->grade_level }}</td>
                            <td>{{ $promo->school_year }}</td>
                            <td>
                                <span class="badge {{ $promo->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($promo->status) }}
                                </span>
                            </td>
                            <td>{{ $promo->updated_at ? $promo->updated_at->format('M d, Y') : '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No promotion history found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $promotions->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
