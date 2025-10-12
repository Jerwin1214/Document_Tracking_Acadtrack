@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- ✅ Popup Messages --}}
    @if (session('success'))
        <x-popup-message type="success" :message="session('success')" />
    @endif
    @if (session('info'))
        <x-popup-message type="info" :message="session('info')" />
    @endif
    @if (session('warning'))
        <x-popup-message type="warning" :message="session('warning')" />
    @endif
    @if (session('error'))
        <x-popup-message type="error" :message="session('error')" />
    @endif

    {{-- ✅ Page Title --}}
    <h2 class="fw-bold mb-4 text-primary">👩‍🏫 All Teachers</h2>

    {{-- ✅ Filter Dropdown --}}
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Filter Teachers</h5>
        <form method="GET" action="{{ route('admin.teachers.index') }}">
            <select name="status" onchange="this.form.submit()"
                    class="form-select shadow-sm border-0 rounded-pill px-3 py-2"
                    style="width: 220px; background-color: #f7f7f7;">
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
        </form>
    </div>

    {{-- ✅ Teachers Table Card --}}
    <div class="card shadow-sm border-0 rounded-4" style="background-color: #fff8e7;">
        <div class="card-header bg-gradient-note text-dark fw-bold py-3">
            <i class="fas fa-chalkboard-teacher me-2"></i> Teachers List
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Subjects</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $i = ($teachers->currentpage() - 1) * $teachers->perpage() + 1;
                            $currentStatus = request('status') ?? 'active';
                        @endphp

                        @foreach ($teachers as $teacher)
                        <tr>
                            <td class="fw-semibold">{{ $i }}</td>
                            <td>{{ $teacher->salutation }} {{ $teacher->initials }} {{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                            <td>
                                @if($teacher->subjects->count())
                                    <span class="subject-codes">
                                        @foreach ($teacher->subjects as $subject)
                                            {{ $subject->code }}@if(!$loop->last), @endif
                                        @endforeach
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-center align-middle">
                                {{-- ✅ Actions Stack --}}
                                <div class="d-grid gap-2" style="min-width: 140px;">
                                    {{-- View --}}
                                    <a href="/admin/teachers/{{ $teacher->id }}"
                                       class="btn btn-sm btn-note-primary rounded-pill shadow-sm text-nowrap">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>

                                    @if ($currentStatus === 'active')
                                        {{-- Edit --}}
                                        <a href="/admin/teachers/{{ $teacher->id }}/edit"
                                           class="btn btn-sm btn-note-warning rounded-pill shadow-sm text-nowrap">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>

                                        {{-- Archive --}}
                                        <form action="{{ route('admin.teachers.archive', $teacher->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to archive this teacher?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-note-secondary rounded-pill shadow-sm text-nowrap">
                                                <i class="fas fa-archive me-1"></i> Archive
                                            </button>
                                        </form>
                                    @elseif ($currentStatus === 'archived')
                                        {{-- Unarchive --}}
                                        <form action="{{ route('admin.teachers.unarchive', $teacher->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Reactivate this teacher?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-note-success rounded-pill shadow-sm text-nowrap">
                                                <i class="fas fa-undo me-1"></i> Unarchive
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ✅ Pagination --}}
        <div class="card-footer bg-white d-flex justify-content-center">
            {{ $teachers->links() }}
        </div>
    </div>
</div>

{{-- ✅ Custom Styles --}}
<style>
    /* Note-like Gradient Header */
    .bg-gradient-note {
        background: linear-gradient(45deg, #fff3cd, #ffe8a1);
    }

    /* Buttons Modern Note Style */
    .btn-note-primary {
        background-color: #ffd966;
        color: #333;
    }
    .btn-note-primary:hover {
        background-color: #ffec99;
        transform: translateY(-2px);
    }

    .btn-note-warning {
        background-color: #facc15;
        color: #333;
    }
    .btn-note-warning:hover {
        background-color: #fde68a;
        transform: translateY(-2px);
    }

    .btn-note-secondary {
        background-color: #d1d5db;
        color: #333;
    }
    .btn-note-secondary:hover {
        background-color: #e5e7eb;
        transform: translateY(-2px);
    }

    .btn-note-success {
        background-color: #86efac;
        color: #333;
    }
    .btn-note-success:hover {
        background-color: #bbf7d0;
        transform: translateY(-2px);
    }

    /* Subject codes clean inline look */
    .subject-codes {
        font-weight: 500;
        color: #444;
        word-break: break-word;
    }

    /* Table row hover */
    table.table-hover tbody tr:hover {
        background-color: #fff4d6;
        transition: background 0.2s ease;
    }
</style>

{{-- ✅ Script --}}
<script>
    $(document).ready(function () {
        $(document).prop('title', 'All Teachers | Student Management System');
    });
</script>
@endsection
