@extends('pages.admin.admin-content')

@section('content')
<div class="container py-4">

    {{-- Popup Messages --}}
    @foreach (['success','info','warning','error'] as $msg)
        @if(session($msg))
            <x-popup-message :type="$msg" :message="session($msg)" />
        @endif
    @endforeach

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="fw-bold text-primary mb-0">
            <i class="fa-solid fa-book-open me-2"></i> Subjects
        </h2>
    </div>

    {{-- Grouped by Department & Grade --}}
    @php
        $groupedSubjects = $subjects->groupBy('level');
    @endphp

    @foreach ($groupedSubjects as $level => $subjectsByLevel)
        <div class="mb-4">
            <h4 class="fw-bold text-secondary text-uppercase border-bottom pb-2">
                {{ ucfirst(str_replace('_', ' ', $level)) }}
            </h4>

            {{-- Group further by grade --}}
            @php
                $byGrade = $subjectsByLevel->groupBy('grade');
            @endphp

            @foreach ($byGrade as $grade => $gradeSubjects)
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header bg-light fw-bold">
                        {{ $grade ? "Grade $grade" : ucfirst($level) }}
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 5%;">#</th>
                                        <th style="width: 30%;">Subject</th>
                                        <th style="width: 15%;">Code</th>
                                        <th style="width: 20%;">Strand</th>
                                        <th style="width: 20%;">Teacher(s)</th>
                                        <th class="text-center" style="width: 10%;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gradeSubjects as $index => $subject)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $subject->name }}</td>
                                            <td><span class="badge bg-secondary">{{ $subject->code }}</span></td>
                                            <td>
                                                @if($subject->strand)
                                                    <span class="badge bg-light text-dark">{{ $subject->strand }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @forelse($subject->teachers as $teacher)
                                                    <span class="badge bg-info text-dark"
                                                          data-bs-toggle="tooltip"
                                                          title="{{ $teacher->salutation }} {{ $teacher->first_name }} {{ $teacher->last_name }}">
                                                        {{ $teacher->first_name[0] }}. {{ $teacher->last_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-muted">—</span>
                                                @endforelse
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.subjects.edit', $subject->id) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fa-solid fa-pen"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endforeach

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $subjects->links() }}
    </div>
</div>

{{-- Script --}}
<script>
    $(document).ready(function () {
        $(document).prop('title', 'Subjects | Student Management System');
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection
