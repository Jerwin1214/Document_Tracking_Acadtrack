<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Document Checklist</title>
    <style>
        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 20px;
        }

        header {
            text-align: center;
            margin-bottom: 15px;
        }

        header h1 {
            font-size: 22px;
            margin: 0;
            color: #1d4ed8; /* Primary color */
        }

        header p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }

        .info {
            margin-bottom: 10px;
            font-size: 12px;
        }

        .info span {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: center;
        }

        table th {
            background-color: #f3f4f6;
            font-weight: 600;
            font-size: 12px;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        table tfoot td {
            font-weight: 600;
            background-color: #e5e7eb;
        }

        .status-submitted {
            background-color: #198754;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }

        .status-no-record {
            background-color: #6c757d;
            color: #fff;
            font-weight: bold;
            border-radius: 4px;
            padding: 2px 6px;
            display: inline-block;
        }

        footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            margin-top: 15px;
        }

        /* Signatory Bar */
        .signatory-bar {
            width: 100%;
            margin-top: 40px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }

        .signatory-line {
            width: 200px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
        }

        .signatory-date {
            font-size: 11px;
            color: #555;
        }

        @media (max-width: 600px) {
            body {
                font-size: 11px;
            }
            .signatory-line {
                width: 150px;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Student Document Report</h1>
    <p>{{ now()->format('F d, Y h:i A') }}</p>
</header>

@if(isset($grade) && $grade != '')
<div class="info">
    <span>Grade Level:</span> {{ $grade }}
</div>
@else
<div class="info">
    <span>Grade Level:</span> All Grades
</div>
@endif



<div class="info">
    <span>Total Students:</span> {{ $totalStudents }}
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>LRN</th>
            <th>Last Name</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Grade Level</th>
            @foreach($allDocuments as $doc)
                <th>{{ $doc->name }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($enrollments as $index => $enrollment)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $enrollment->lrn }}</td>
            <td>{{ $enrollment->last_name }}</td>
            <td>{{ $enrollment->first_name }}</td>
            <td>{{ $enrollment->middle_name }}</td>
            <td>{{ $enrollment->grade_level }}</td>
            @foreach($allDocuments as $doc)
                @php
                    $studentDoc = $enrollment->studentDocuments
                        ->where('document_id', $doc->id)
                        ->sortByDesc('id')
                        ->first();
                @endphp
                <td>
                    @if($studentDoc)
                        @if($studentDoc->status === 'Submitted')
                            <span class="status-submitted">Submitted</span>
                        @elseif($studentDoc->status === 'Pending')
                            <span class="status-pending">Pending</span>
                        @else
                            <span class="status-no-record">{{ $studentDoc->status }}</span>
                        @endif
                    @else
                        <span class="status-no-record">No Record</span>
                    @endif
                </td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6">Total Submitted</td>
            @foreach($allDocuments as $doc)
                @php
                    $submittedCount = $enrollments->sum(function($enrollment) use ($doc) {
                        $studentDoc = $enrollment->studentDocuments
                            ->where('document_id', $doc->id)
                            ->sortByDesc('id')
                            ->first();
                        return $studentDoc && $studentDoc->status === 'Submitted' ? 1 : 0;
                    });
                @endphp
                <td>{{ $submittedCount }}</td>
            @endforeach
        </tr>
        <tr>
            <td colspan="6">Total Pending / No Record</td>
            @foreach($allDocuments as $doc)
                @php
                    $pendingCount = $enrollments->sum(function($enrollment) use ($doc) {
                        $studentDoc = $enrollment->studentDocuments
                            ->where('document_id', $doc->id)
                            ->sortByDesc('id')
                            ->first();
                        return (!$studentDoc || $studentDoc->status !== 'Submitted') ? 1 : 0;
                    });
                @endphp
                <td>{{ $pendingCount }}</td>
            @endforeach
        </tr>

        {{-- Grand Totals --}}
        <tr>
            <td colspan="6">Grand Total Submitted</td>
            @php
                $grandSubmitted = $enrollments->sum(function($enrollment) use ($allDocuments) {
                    return $allDocuments->sum(function($doc) use ($enrollment) {
                        $studentDoc = $enrollment->studentDocuments
                            ->where('document_id', $doc->id)
                            ->sortByDesc('id')
                            ->first();
                        return $studentDoc && $studentDoc->status === 'Submitted' ? 1 : 0;
                    });
                });
            @endphp
            <td colspan="{{ $allDocuments->count() }}">{{ $grandSubmitted }}</td>
        </tr>
        <tr>
            <td colspan="6">Grand Total Pending / No Record</td>
            @php
                $grandPending = $enrollments->sum(function($enrollment) use ($allDocuments) {
                    return $allDocuments->sum(function($doc) use ($enrollment) {
                        $studentDoc = $enrollment->studentDocuments
                            ->where('document_id', $doc->id)
                            ->sortByDesc('id')
                            ->first();
                        return (!$studentDoc || $studentDoc->status !== 'Submitted') ? 1 : 0;
                    });
                });
            @endphp
            <td colspan="{{ $allDocuments->count() }}">{{ $grandPending }}</td>
        </tr>
    </tfoot>
</table>

<footer>
    Acadtrack Digital Document Tracking System of Lyceum of Lal-lo
</footer>

{{-- Signatory Bar --}}
<div class="signatory-bar">
    <div class="signatory-line"></div>
    <div class="signatory-date">{{ now()->format('F d, Y') }}</div>
</div>

<style>
/* Signatory Bar Styling */
.signatory-bar {
    width: 100%;
    margin-top: 40px;
    display: flex;
    justify-content: center;
    flex-direction: column;
    align-items: center;
}
.signatory-line {
    width: 200px;
    border-bottom: 1px solid #333;
    margin-bottom: 5px;
}
.signatory-date {
    font-size: 11px;
    color: #555;
}
@media (max-width: 600px) {
    .signatory-line {
        width: 150px;
    }
}
</style>

</body>
</html>
