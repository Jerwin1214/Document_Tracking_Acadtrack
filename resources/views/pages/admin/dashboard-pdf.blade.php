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
            margin: 40px;
            background-color: #fff;
        }
        h1, h2, h3 {
            color: #1e272e;
            margin-bottom: 5px;
        }
        h1 {
            font-size: 22px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        h3 {
            border-left: 4px solid #2f3542;
            padding-left: 8px;
            margin-top: 25px;
            font-size: 14px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #2f3542;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header p {
            font-size: 11px;
            color: #57606f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 6px;
            overflow: hidden;
        }
        th, td {
            padding: 10px 12px;
            border-bottom: 1px solid #dfe4ea;
            text-align: left;
        }
        th {
            background-color: #2f3542;
            color: #fff;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }
        tr:nth-child(even) {
            background-color: #f1f2f6;
        }
        .text-success { color: #2ed573; font-weight: bold; }
        .text-warning { color: #ffa502; font-weight: bold; }
        .text-danger { color: #ff4757; font-weight: bold; }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 11px;
            color: #57606f;
            border-top: 1px solid #ced6e0;
            padding-top: 10px;
        }
        /* Signatory Bar */
        .signatory-bar {
            width: 100%;
            margin-top: 50px;
            display: flex;
            justify-content: center;
            flex-direction: column;
            align-items: center;
        }
        .signatory-line {
            width: 200px;
            border-bottom: 1px solid #2f3542;
            margin-bottom: 5px;
        }
        .signatory-date {
            font-size: 11px;
            color: #57606f;
        }
        @media (max-width: 600px) {
            body {
                margin: 20px;
                font-size: 11px;
            }
            .signatory-line {
                width: 150px;
            }
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>Acadtrack Dashboard Report</h1>
      <p>Generated on {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</p>
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

    {{-- Submission Trend --}}
    <h3>Submission Trend (Monthly by Document)</h3>
    <table>
        <thead>
            <tr>
                <th>Document</th>
                @foreach($submissionTrendLabels ?? [] as $month)
                    <th>{{ $month }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($allDocuments ?? [] as $doc)
                <tr>
                    <td>{{ $doc->name }}</td>
                    @foreach($submissionTrendData[$doc->id] ?? [] as $count)
                        <td>{{ $count }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Monthly Uploads --}}
    <h3>Monthly Document Uploads</h3>
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th>Uploads</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyUploads ?? [] as $month => $count)
            <tr>
                <td>{{ $month }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Yearly Uploads --}}
    <h3>Yearly Document Uploads</h3>
    <table>
        <thead>
            <tr>
                <th>Year</th>
                <th>Total Uploads</th>
            </tr>
        </thead>
        <tbody>
            @foreach($yearlyUploads ?? [] as $year => $count)
            <tr>
                <td>{{ $year }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

{{-- Students by Grade --}}
<h3>Student Population </h3>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Grade Level</th>
            <th>Students</th>
            <th>Percentage</th>
        </tr>
    </thead>
    <tbody>
        @foreach($studentsByGrade ?? [] as $grade => $count)
        <tr>
            <td>{{ $grade }}</td>
            <td>{{ $count }}</td>
            <td>{{ number_format(($count / max($totalStudents, 1)) * 100, 2) }}%</td>
        </tr>
        @endforeach
        <tr style="font-weight:bold; background-color:#f0f0f0;">
            <td>Total</td>
            <td>{{ $totalStudents }}</td>
            <td>100%</td>
        </tr>
    </tbody>
</table>


    {{-- Footer --}}
    <div class="footer">
        <p><strong>Acadtrack Digital Document Tracking System of Lyceum of Lal-lo</p>
    </div>

    {{-- Signatory Bar --}}
    <div class="signatory-bar">
        <div class="signatory-line"></div>
        <div class="signatory-date">{{ now()->setTimezone('Asia/Manila')->format('F d, Y') }}</div>
    </div>

</body>
</html>
