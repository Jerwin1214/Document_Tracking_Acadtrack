<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Document Report</title>
<style>
body {
    font-family: 'Helvetica', Arial, sans-serif;
    font-size: 12px;
    color: #333;
    margin: 20px;
}

/* Header & Circular Logo */
header {
    width: 100%;
    text-align: center;
    margin-bottom: 15px;
}

header img,
header h1,
header h2,
header p {
    display: block;
    margin-left: auto;
    margin-right: auto;
}

header img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 5px;
}

header h1 {
    font-size: 18px;
    text-transform: uppercase;
    margin: 5px 0 0 0;
    color: #1d4ed8;
}

header h2 {
    font-size: 14px;
    font-weight: normal;
    margin: 2px 0 0 0;
    color: #1d4ed8;
}

header p {
    font-size: 10px;
    color: #555;
    margin: 2px 0 0 0;
}

/* Info Section */
.info {
    margin-bottom: 10px;
    font-size: 12px;
    text-align: left;
}

.grade-level {
    text-align: center;
}

.info span {
    font-weight: bold;
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-size: 12px;
}

table th, table td {
    border: 1px solid #ddd;
    padding: 8px;
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

/* Status Text Only */
.status-submitted {
    color: #198754; /* green text */
    font-weight: bold;
}

.status-pending {
    color: #d97706; /* orange text */
    font-weight: bold;
}

.status-no-record {
    color: #6c757d; /* gray text */
    font-weight: bold;
}

/* Footer */
footer {
    text-align: center;
    font-size: 10px;
    color: #555;
    margin-top: 15px;
}

/* Professional Signatory */
.signatory-container {
    width: 100%;
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

.signatory-name-block {
    text-align: center;
    width: 240px;
}

.signatory-line {
    width: 100%;
    border-bottom: 1.5px solid #2f3542;
    margin-bottom: 8px;
}

.signatory-fullname {
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 2px;
}

.signatory-position {
    font-size: 11px;
    margin-bottom: 2px;
}

.signatory-date {
    font-size: 11px;
    color: #555;
}

@media (max-width: 600px) {
    body {
        font-size: 11px;
    }
    .signatory-name-block {
        width: 180px;
    }
}

/* Timestamp at bottom right */
.generated-timestamp {
    position: fixed;
    bottom: 10px;
    right: 20px;
    font-size: 10px;
    color: #555;
}
</style>

</head>
<body>

<header>
    <img src="{{ public_path('images/acadtracklogo.jpg') }}" alt="Acadtrack Logo">
    <h1>Student Document Report</h1>
    <h2>Lyceum of Lal-lo</h2>
    <p style="font-size: 10px; color: #555; margin: 2px 0 0 0;">
        Centro Lal-lo, Cagayan, Philippines
    </p>
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

{{-- Professional Signatory --}}
@if(isset($signatory))
<div class="signatory-container">
    <div class="signatory-name-block">
        <div class="signatory-line"></div>
        <div class="signatory-fullname">
            {{ $signatory->first_name }}
            @if($signatory->middle_initial) {{ $signatory->middle_initial }}. @endif
            {{ $signatory->last_name }}{{ $signatory->educational_attainment ? ', '.$signatory->educational_attainment : '' }}
        </div>
        <div class="signatory-position">{{ $signatory->position }}</div>
        <div class="signatory-date">
            Signed on {{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}
        </div>
    </div>
</div>
@endif

<footer>
    Acadtrack Digital Document Tracking System of Lyceum of Lal-lo
</footer>
<div class="generated-timestamp">
    Generated on {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}
</div>

</body>
</html>
