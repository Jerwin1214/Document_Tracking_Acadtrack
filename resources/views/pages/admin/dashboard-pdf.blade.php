<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Acadtrack Dashboard Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #2f3542;
            font-size: 12px;
            margin: 30px;
            background-color: #fff;
        }

        /* Header & Circular Logo */
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 80px;
            height: 80px;
            border-radius: 50%; /* Circular logo */
            object-fit: cover;
            margin-bottom: 5px;
        }
        .header h1 {
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        .header p {
            font-size: 11px;
            color: #57606f;
            margin: 2px 0 0 0;
        }

        h3 {
            border-left: 4px solid #2f3542;
            padding-left: 8px;
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 12px;
        }
        th, td {
            padding: 6px 8px;
            border: 1px solid #dfe4ea;
            text-align: left;
        }
        th {
            background-color: #2f3542;
            color: #fff;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) td {
            background-color: #f1f2f6;
        }

        .text-success { color: #2ed573; font-weight: bold; }
        .text-warning { color: #ffa502; font-weight: bold; }
        .text-danger { color: #ff4757; font-weight: bold; }

        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #57606f;
            border-top: 1px solid #ced6e0;
            padding-top: 8px;
        }

        /* Professional Signatory */
        .signatory-container {
            width: 100%;
            margin-top: 30px;
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
            margin: 8px 0 4px 0;
        }
        .signatory-fullname {
            font-size: 12px;
            letter-spacing: 0.3px;
        }
        .signatory-position {
            font-size: 11px;
            color: #57606f;
            margin-top: 2px;
            font-style: italic;
        }
        .signatory-date {
            font-size: 10px;
            margin-top: 6px;
            color: #747d8c;
        }

        @media print {
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
        }

        .header h2 {
    font-size: 14px;
    margin: 2px 0 0 0;
    font-weight: normal;
    color: #2f3542;
}
.generated-date {
    position: fixed;
    bottom: 20px;
    right: 30px;
    font-size: 11px;
    color: #57606f;
}


    </style>
</head>
<body>

 {{-- Header --}}
<div class="header">
    <img src="{{ public_path('images/acadtracklogo.jpg') }}" alt="Acadtrack Logo">
    <h1>Acadtrack Dashboard Report</h1>
    <h2>Lyceum of Lal-lo</h2>
    <p style="font-size: 10px; margin: 2px 0 0 0; color: #57606f;">
        Centro Lal-lo, Cagayan, Philippines
    </p>
</div>


    {{-- Summary Overview --}}
    <h3>Summary Overview</h3>
    <table>
        <thead>
            <tr>
                <th>Total Students</th>
                <th>Documents Submitted</th>
                <th>Documents Pending</th>
                <th>Pending (1 Year Above)</th>
                <th>Near Deadline</th>
                <th>Completion Rate</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalStudents ?? 0 }}</td>
                <td class="text-success">{{ $submittedDocs ?? 0 }}</td>
                <td class="text-warning">{{ $pendingDocs ?? 0 }}</td>
                <td class="text-danger">{{ $pendingOverYear ?? 0 }}</td>
                <td class="text-warning">{{ $nearDeadline ?? 0 }}</td>
                <td class="text-success">{{ number_format($completionRate ?? 0, 2) }}%</td>
            </tr>
        </tbody>
    </table>

    {{-- Documents per Type --}}
    <h3>Documents per Type</h3>
    <table>
        <thead>
            <tr>
                <th>Document Type</th>
                <th>Submitted</th>
                <th>Pending</th>
                <th>Completion %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($documentsPerType ?? [] as $type => $data)
            <tr>
                <td>{{ $type }}</td>
                <td class="text-success">{{ $data['submitted'] }}</td>
                <td class="text-warning">{{ $data['pending'] }}</td>
                <td>{{ number_format($data['completion'], 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pending Duration --}}
    <h3>Pending Duration Overview</h3>
    <table>
        <thead>
            <tr>
                <th>Duration Range</th>
                <th>Pending Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pendingDuration ?? [] as $range => $count)
            <tr>
                <td>{{ $range }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

{{-- Student Population --}}
<h3>Student Population</h3>
<table>
    <thead>
        <tr>
            <th>Grade Level</th>
            <th>Students</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @php
            // Define ascending grade order exactly as in your code
            $grades = [
                'Kindergarten', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4',
                'Grade 5', 'Grade 6', 'Grade 7', 'Grade 8', 'Grade 9',
                'Grade 10', 'Grade 11', 'Grade 12'
            ];
        @endphp

        @foreach($grades as $grade)
        <tr>
            <td>{{ $grade }}</td>
            <td>{{ $studentsByGrade[$grade] ?? 0 }}</td>
            <td>{{ number_format((($studentsByGrade[$grade] ?? 0) / max($totalStudents,1)) * 100, 2) }}%</td>
        </tr>
        @endforeach

        <tr style="font-weight:bold; background-color:#f0f0f0;">
            <td>Total</td>
            <td>{{ $totalStudents }}</td>
            <td>100%</td>
        </tr>
    </tbody>
</table>

    {{-- Professional Signatory --}}
    @if(isset($signatory))
    <div class="signatory-container">
        <div class="signatory-name-block">
            <div class="signatory-line"></div>
            <div class="signatory-fullname">
                <strong>
                    {{ $signatory->first_name }}
                    @if($signatory->middle_initial) {{ $signatory->middle_initial }}. @endif
                    {{ $signatory->last_name }}{{ $signatory->educational_attainment ? ', '.$signatory->educational_attainment : '' }}
                </strong>
            </div>
            <div class="signatory-position">{{ $signatory->position }}</div>
            <div class="signatory-date">
                Signed on {{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}
            </div>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="footer">
        <p><strong>Acadtrack Digital Document Tracking System of Lyceum of Lal-lo</strong></p>
    </div>
<div class="generated-date">
    Generated on {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}
</div>
</body>
</html>
