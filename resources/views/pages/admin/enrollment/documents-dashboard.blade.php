@extends('pages.admin.admin-content')

@section('content')

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<div class="card shadow-lg border-0 mb-4 p-5 text-center"
     style="background: linear-gradient(135deg, #1a1a2e, #16213e); color: #e0e0e0; border-radius: 18px; font-family: 'Poppins', sans-serif; transition: transform 0.3s ease, box-shadow 0.3s ease;">
    <h2 class="fw-bold mb-2"
        style="font-size: 1.9rem; letter-spacing: 1px; color: #ffffff; text-shadow: 0 2px 6px rgba(0,0,0,0.4);">
        Welcome to <span style="color: #00adb5;">Acadtrack</span> Digital Document Tracking System
    </h2>
    <p class="mb-3 fs-5"
       style="color: #dcdde1; font-weight: 400; font-size: 1.1rem;">
        Manage your school's documents efficiently and securely.
    </p>
    <div id="dateTime"
         class="fw-semibold fs-6"
         style="color: #00adb5; letter-spacing: 0.5px; margin-top: 10px;">
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        document.getElementById('dateTime').textContent = now.toLocaleString('en-US', options);
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 173, 181, 0.3);
    }
</style>


<div class=".dashboard-wrapper" style="background-color:#2f3542; color:#f1f2f6;padding:20px 15px;min-height:100vh;">
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.dashboard.printReport') }}" target="_blank" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> PDF Report
    </a>
</div>

    {{-- ✅ Summary Cards --}}
    <div class="row g-4 mb-5 justify-content-start">
        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-dark text-white">
                <div class="card-body">
                    <div class="icon-circle bg-primary mb-3">
                        <i class="fas fa-users fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $enrollments->count() }}</h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-dark text-white">
                <div class="card-body">
                    <div class="icon-circle bg-success mb-3">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0">
                        {{ $enrollments->flatMap(fn($e) => $e->studentDocuments)->where('status','Submitted')->count() }}
                    </h3>
                    <p class="mb-0">Documents Submitted</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-dark text-white">
                <div class="card-body">
                    <div class="icon-circle bg-warning mb-3">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0">
                        {{ $enrollments->flatMap(fn($e) => $e->studentDocuments)->where('status','Pending')->count() }}
                    </h3>
                    <p class="mb-0">Documents Pending</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-dark text-white">
                <div class="card-body">
                    <div class="icon-circle bg-danger mb-3">
                        <i class="fas fa-exclamation-triangle fa-lg"></i>
                    </div>
                    @php
                        $oneYearWarning = $enrollments->flatMap(fn($e) => $e->studentDocuments)
                            ->filter(fn($d) => $d->status === 'Pending' && $d->created_at->diffInDays(now()) > 365)
                            ->count();
                    @endphp
                    <h3 class="fw-bold mb-0">{{ $oneYearWarning }}</h3>
                    <p class="mb-0">Pending 1 year Above</p>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-warning text-dark">
                <div class="card-body">
                    <div class="icon-circle bg-danger mb-3">
                        <i class="fas fa-bell fa-lg"></i>
                    </div>
                    @php
                        $approachingDeadline = $enrollments->flatMap(fn($e) => $e->studentDocuments)
                            ->filter(fn($d) => $d->status === 'Pending' && $d->created_at->diffInDays(now()) > 335)
                            ->count();
                    @endphp
                    <h3 class="fw-bold mb-0">{{ $approachingDeadline }}</h3>
                    <p class="mb-0">Near Deadline</p>
                </div>
            </div>
        </div>
    </div>

 {{-- ✅ Charts Section --}}
<div class="row g-4">
    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Document Completion</h5>
                <div class="chart-container">
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Documents per Type</h5>
                <div class="chart-container">
                    <canvas id="documentsBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Pending Duration</h5>
                <div class="chart-container">
                    <canvas id="pendingDurationChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Submission Trend</h5>
                <div class="chart-container">
                    <canvas id="submissionTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- % of Students Submitted & Document Uploads Row --}}
<div class="row mt-4 g-4">
    <div class="col-lg-6 col-md-12">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Percentage of Students Submitted Each Document</h5>
                <div class="chart-container">
                    <canvas id="submissionPercentageChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Document Uploads</h5>
                <div class="chart-container">
                    <canvas id="uploadsChart"></canvas>
                </div>
                <div class="mt-2 d-flex justify-content-end flex-wrap gap-1">
                    <button class="btn btn-sm btn-primary" id="monthlyBtn">Monthly</button>
                    <button class="btn btn-sm btn-secondary" id="yearlyBtn">Yearly</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Overall Completion Rate --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body text-center">
                <h5 class="fw-bold mb-3 text-white">Overall Document Completion Rate</h5>
                @php
                    $totalDocs = $enrollments->flatMap(fn($e) => $e->studentDocuments)->count();
                    $completedDocs = $enrollments->flatMap(fn($e) => $e->studentDocuments)->where('status','Submitted')->count();
                    $completionRate = $totalDocs ? round(($completedDocs/$totalDocs)*100,2) : 0;
                @endphp
                <h2 class="fw-bold text-success">{{ $completionRate }}%</h2>
                <div class="progress mx-auto" style="height:25px; max-width:500px; background-color:#57606f;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $completionRate }}%" aria-valuenow="{{ $completionRate }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Student Population by Grade Chart --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-dark">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-white">Student Population by Grade Level</h5>
                <div class="chart-container">
                    <canvas id="studentsByGradeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Styles --}}
<style>
.hover-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.hover-card:hover { transform: translateY(-5px); box-shadow: 0 6px 20px rgba(0,0,0,0.4); }
.icon-circle { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 50%; margin: 0 auto; }
.bg-success { background-color: #2ed573 !important; }
.bg-warning { background-color: #ffa502 !important; }
.bg-danger { background-color: #ff4757 !important; }
.bg-primary { background-color: #1e90ff !important; }
.bg-info { background-color: #3742fa !important; }
.bg-secondary { background-color: #57606f !important; }
.card { background-color: #2f3542 !important; color: #f1f2f6 !important; }
chart-container {
    position: relative;
    width: 100%;
    min-height: 350px; /* ensures charts are readable */
}
@media (max-width: 768px) {
    .chart-container { min-height: 300px; }
}

</style>

{{-- ✅ Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {


    const statusCounts = {
        'Submitted': @json($enrollments->flatMap(fn($e) => $e->studentDocuments)->where('status','Submitted')->count()),
        'Pending': @json($enrollments->flatMap(fn($e) => $e->studentDocuments)->where('status','Pending')->count()),
        'Pending >1 Year': @json($enrollments->flatMap(fn($e) => $e->studentDocuments)->filter(fn($d) => $d->status==='Pending' && $d->created_at->diffInDays(now())>365)->count())
    };

    const documentsCounts = {
        @foreach($allDocuments as $doc)
            @php
                $docName = str_ireplace(['photocopy original card','Photocopy Original Card'],'Card',$doc->name);
            @endphp
            '{{ $docName }}': @json($enrollments->filter(fn($e)=>optional($e->studentDocuments->firstWhere('document_id',$doc->id))?->status==='Submitted')->count()),
        @endforeach
    };

    const pendingDurationCounts = {
        @foreach($allDocuments as $doc)
            @php
                $docName = str_ireplace(['photocopy original card','Photocopy Original Card'],'Card',$doc->name);
            @endphp
            '{{ $docName }}': @json($enrollments->filter(fn($e)=>optional($e->studentDocuments->firstWhere('document_id',$doc->id))?->status==='Pending')->count()),
        @endforeach
    };

    const submissionPercentage = {
        @foreach($allDocuments as $doc)
            @php
                $docName = str_ireplace(['photocopy original card','Photocopy Original Card'],'Card',$doc->name);
            @endphp
            '{{ $docName }}': @json($enrollments->count() ? round($enrollments->filter(fn($e)=>optional($e->studentDocuments->firstWhere('document_id',$doc->id))?->status==='Submitted')->count()/$enrollments->count()*100,2) : 0),
        @endforeach
    };

    const monthlyUploadsCounts = @json($monthlyUploads ?? []);
    const yearlyUploadsCounts = @json($yearlyUploads ?? []);

    const docColors = ['#1e90ff','#ff4757','#2ed573','#ffa502','#3742fa','#e84393','#00cec9'];

    // ✅ Submission Percentage Chart (with hover tooltip update)
    new Chart(document.getElementById('submissionPercentageChart').getContext('2d'), {
        type:'bar',
        data:{
            labels:Object.keys(submissionPercentage),
            datasets:[{
                label:'Percentage of Students Submitted Each Document',
                data:Object.values(submissionPercentage),
                backgroundColor:'#3742fa',
                borderRadius:5
            }]
        },
        options:{
            responsive:true,
            plugins:{
                legend:{ display:false },
                tooltip:{
                    callbacks:{
                        label:function(context){
                            return `Students Submitted: ${context.parsed.y}% `;
                        }
                    }
                }
            },
            scales:{
                y:{ beginAtZero:true, ticks:{ color:'#f1f2f6', callback:value=>value+"%" } },
                x:{ ticks:{ color:'#f1f2f6' } }
            }
        }
    });
 // ✅ Student Population by Grade Chart
    const studentsByGrade = @json($studentsByGrade);
    const grades = Object.keys(studentsByGrade);
    const gradeCounts = Object.values(studentsByGrade);
    const ctxGrade = document.getElementById('studentsByGradeChart').getContext('2d');

    new Chart(ctxGrade, {
        type: 'bar',
        data: {
            labels: grades,
            datasets: [{
                label: 'Number of Students',
                data: gradeCounts,
                backgroundColor: '#1e90ff',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: function(context){ return context.parsed.y + ' Students'; } } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { color:'#f1f2f6' } },
                x: { ticks: { color:'#f1f2f6' } }
            }
        }
    });


    const submissionTrendCtx = document.getElementById('submissionTrendChart').getContext('2d');
    const submissionTrendLabels = @json($submissionTrendLabels ?? []);
    const submissionTrendDataSets = [];
    @foreach($allDocuments as $index => $doc)
        @php
            $docName = str_ireplace(['photocopy original card','Photocopy Original Card'],'Card',$doc->name);
        @endphp
        submissionTrendDataSets.push({
            label: '{{ $docName }}',
            data: @json($submissionTrendData[$doc->id] ?? []),
            borderColor: docColors[{{ $index }} % docColors.length],
            backgroundColor: docColors[{{ $index }} % docColors.length] + '33',
            fill: true,
            tension:0.3,
            pointRadius:4
        });
    @endforeach

    new Chart(submissionTrendCtx, {
        type:'line',
        data:{ labels: submissionTrendLabels, datasets: submissionTrendDataSets },
        options:{ responsive:true, plugins:{ legend:{ labels:{ color:'#f1f2f6' } } }, scales:{ y:{ beginAtZero:true, ticks:{ color:'#f1f2f6' } }, x:{ ticks:{ color:'#f1f2f6' } } } }
    });

    new Chart(document.getElementById('statusPieChart').getContext('2d'), {
        type:'doughnut',
        data:{ labels:Object.keys(statusCounts), datasets:[{ data:Object.values(statusCounts), backgroundColor:['#2ed573','#ffa502','#ff6b81'] }] },
        options:{ responsive:true, plugins:{ legend:{ position:'bottom', labels:{ color:'#f1f2f6' } } } }
    });

    new Chart(document.getElementById('documentsBarChart').getContext('2d'), {
        type:'bar',
        data:{ labels:Object.keys(documentsCounts), datasets:[{ label:'Completed Documents', data:Object.values(documentsCounts), backgroundColor:'#1e90ff', borderRadius:5 }] },
        options:{ responsive:true, plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true, ticks:{ color:'#f1f2f6' } }, x:{ ticks:{ color:'#f1f2f6' } } } }
    });

    new Chart(document.getElementById('pendingDurationChart').getContext('2d'), {
        type:'line',
        data:{ labels:Object.keys(pendingDurationCounts), datasets:[{ label:'Pending Documents', data:Object.values(pendingDurationCounts), borderColor:'#ffa502', backgroundColor:'rgba(255,165,2,0.2)', fill:true, tension:0.3, pointRadius:5 }] },
        options:{ responsive:true, plugins:{ legend:{ labels:{ color:'#f1f2f6' } } }, scales:{ y:{ beginAtZero:true, ticks:{ color:'#f1f2f6' } }, x:{ ticks:{ color:'#f1f2f6' } } } }
    });

    const uploadsChartCtx = document.getElementById('uploadsChart').getContext('2d');
    let uploadsChart = new Chart(uploadsChartCtx, {
        type:'line',
        data:{ labels:Object.keys(monthlyUploadsCounts), datasets:[{ label:'Document Uploads', data:Object.values(monthlyUploadsCounts), borderColor:'#3742fa', backgroundColor:'rgba(55,66,250,0.2)', fill:true, tension:0.3, pointRadius:5 }] },
        options:{ responsive:true, plugins:{ legend:{ labels:{ color:'#f1f2f6' } } }, scales:{ y:{ beginAtZero:true, ticks:{ color:'#f1f2f6' } }, x:{ ticks:{ color:'#f1f2f6' } } } }
    });

    document.getElementById('monthlyBtn').addEventListener('click', ()=>{
        uploadsChart.data.labels = Object.keys(monthlyUploadsCounts);
        uploadsChart.data.datasets[0].data = Object.values(monthlyUploadsCounts);
        uploadsChart.update();
    });

    document.getElementById('yearlyBtn').addEventListener('click', ()=>{
        uploadsChart.data.labels = Object.keys(yearlyUploadsCounts);
        uploadsChart.data.datasets[0].data = Object.values(yearlyUploadsCounts);
        uploadsChart.update();
    });

});
</script>

@endsection
