@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">
        📊 Grades for {{ $class->year_level }} - {{ $class->section }}
        @if($class->strand)
            ({{ $class->strand }})
        @endif
        @if(isset($subject))
            - {{ $subject->name }}
        @endif
    </h2>

    @php
        $isKindergarten = strtolower($class->year_level) === 'kindergarten';
        $department = $isKindergarten ? 'Kindergarten' : ($class->year_level <= 6 ? 'Elementary' : ($class->year_level <= 10 ? 'Junior High' : 'Senior High'));
        $quarters = [1,2,3,4]; // For display purposes
        $column = $subject ? ($subjectColumns[$department][$subject->name] ?? null) : null;
    @endphp

    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>LRN</th>
                <th>Name</th>
                @foreach($quarters as $q)
                    <th>Q{{ $q }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    if($isKindergarten){
                        // Kindergarten: get all quarters keyed by quarter
                        $gradeRows = $student->kinderGrades
                                             ->where('school_year', $schoolYear ?? now()->year)
                                             ->keyBy('quarter');
                    } else {
                        // Other levels: only get the row for selected grading quarter & school year
                        $gradeRows = collect();
                        if(isset($gradingQuarter)){
                            $gradeRows[1] = $student->{$gradeRelation}
                                                    ->where('school_year', $schoolYear ?? now()->year)
                                                    ->where('grading_quarter', $gradingQuarter)
                                                    ->first();
                        }
                    }
                @endphp

                <tr>
                    <td>{{ $student->lrn }}</td>
                    <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>

                    @foreach($quarters as $q)
                        @php
                            $gradeRow = $gradeRows[$q] ?? null;
                            $gradeValue = '-';
                            if($gradeRow && $column){
                                $gradeValue = $gradeRow->$column ?? '-';
                            }
                        @endphp
                        <td class="text-center">{{ $gradeValue }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($quarters) + 2 }}" class="text-center text-muted">
                        No students enrolled in this class.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        <a href="{{ route('teacher.grades.view.select-class') }}" class="btn btn-secondary">← Back to Classes</a>
    </div>
</div>
@endsection
