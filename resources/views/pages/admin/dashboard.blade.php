@extends('pages.admin.admin-content')

@section('content')

{{-- ✅ Dynamic Welcome Greeting --}}
@if (session('greeting'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    Toast.fire({
        icon: "success",
        title: "{{ session('greeting') }}"
    });
</script>
@endif

<div class="container-fluid py-4">
    <h2 class="fw-bold mb-4">📊 Admin Dashboard</h2>

    {{-- ✅ Summary Cards --}}
    <div class="row g-4">
        {{-- Total Students --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="icon-circle bg-white text-success mb-3">
                        <i class="fas fa-user-graduate fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="student_count">{{ $counts->students_count ?? 0 }}</h3>
                    <p class="mb-0">Total Students</p>
                </div>
            </div>
        </div>

        {{-- Total Teachers --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="icon-circle bg-white text-primary mb-3">
                        <i class="fas fa-chalkboard-teacher fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $counts->teachers_count ?? 0 }}</h3>
                    <p class="mb-0">Total Teachers</p>
                </div>
            </div>
        </div>

        {{-- Total Subjects --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="icon-circle bg-white text-warning mb-3">
                        <i class="fas fa-book fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0" id="subject_count">{{ $counts->subjects_count ?? 0 }}</h3>
                    <p class="mb-0">Total Subjects</p>
                </div>
            </div>
        </div>

        {{-- Total Classes --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0 text-center h-100 hover-card bg-gradient-purple text-white">
                <div class="card-body">
                    <div class="icon-circle bg-white text-purple mb-3">
                        <i class="fas fa-school fa-lg"></i>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $counts->classes_count ?? 0 }}</h3>
                    <p class="mb-0">Total Classes</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Charts Section --}}
    <div class="row mt-5 g-4">
        {{-- Students Chart --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">📈 Students Overview</h5>
                    <div class="mb-3">
                        <label for="chartToggle" class="form-label">View By:</label>
                        <select id="chartToggle" class="form-select">
                            <option value="department">Department</option>
                            <option value="grade">Grade Level</option>
                            <option value="strand">Senior High (Strand)</option>
                        </select>
                    </div>
                    <div style="height:350px;">
                        <canvas id="studentDepartmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Gender Distribution --}}
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">👩‍🎓👨‍🎓 Gender Distribution</h5>
                    <div style="height:350px;">
                        <canvas id="genderChart"></canvas>
                    </div>
                    <p class="mt-3 text-center text-muted">
                        Total Students: <strong>{{ $counts->students_count ?? 0 }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Custom Styles --}}
<style>
    .hover-card { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    .icon-circle {
        width: 60px; height: 60px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        margin: 0 auto;
    }
    .bg-gradient-success { background: linear-gradient(45deg,#28a745,#6fda44)!important; }
    .bg-gradient-primary { background: linear-gradient(45deg,#007bff,#5bc0de)!important; }
    .bg-gradient-warning { background: linear-gradient(45deg,#fd7e14,#f9b233)!important; }
    .bg-gradient-purple { background: linear-gradient(45deg,#6f42c1,#9d6efd)!important; }

    .text-purple { color: #6f42c1 !important; }
</style>

{{-- ✅ Chart Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        $(document).prop('title', 'Admin Dashboard | Acadtrack System');
    });

    // Chart datasets
    const chartDataSets = {
        department: {
            labels: ['Kindergarten','Elementary','Junior High','Senior High'],
            data: [{{ $departmentCounts['Kindergarten'] ?? 0 }},{{ $departmentCounts['Elementary'] ?? 0 }},{{ $departmentCounts['Junior High'] ?? 0 }},{{ $departmentCounts['Senior High'] ?? 0 }}],
            title: 'Students per Department'
        },
        grade: {
            labels: ['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'],
            data: [{{ $gradeCounts['Kindergarten'] ?? 0 }},{{ $gradeCounts['Grade 1'] ?? 0 }},{{ $gradeCounts['Grade 2'] ?? 0 }},{{ $gradeCounts['Grade 3'] ?? 0 }},{{ $gradeCounts['Grade 4'] ?? 0 }},{{ $gradeCounts['Grade 5'] ?? 0 }},{{ $gradeCounts['Grade 6'] ?? 0 }},{{ $gradeCounts['Grade 7'] ?? 0 }},{{ $gradeCounts['Grade 8'] ?? 0 }},{{ $gradeCounts['Grade 9'] ?? 0 }},{{ $gradeCounts['Grade 10'] ?? 0 }},{{ $gradeCounts['Grade 11'] ?? 0 }},{{ $gradeCounts['Grade 12'] ?? 0 }}],
            title: 'Students per Grade Level'
        },
        strand: {
            labels: ['Grade 11 ABM','Grade 11 STEM','Grade 11 HUMSS','Grade 11 GAS','Grade 12 ABM','Grade 12 STEM','Grade 12 HUMSS','Grade 12 GAS'],
            data: [{{ $seniorHighStrandCounts['Grade 11 ABM'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 11 STEM'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 11 HUMSS'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 11 GAS'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 12 ABM'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 12 STEM'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 12 HUMSS'] ?? 0 }},{{ $seniorHighStrandCounts['Grade 12 GAS'] ?? 0 }}],
            title: 'Senior High by Strand'
        }
    };

    // Students Bar Chart
    const ctx = document.getElementById('studentDepartmentChart').getContext('2d');
    let studentDepartmentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartDataSets.department.labels,
            datasets: [{
                label: 'No. of Students',
                data: chartDataSets.department.data,
                backgroundColor: 'rgba(78, 115, 223, 0.7)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1000, easing: 'easeOutQuart' },
            plugins: {
                legend: { display: false },
                title: { display: true, text: chartDataSets.department.title }
            },
            scales: { y: { beginAtZero: true } }
        }
    });

    // Toggle Chart
    document.getElementById('chartToggle').addEventListener('change', function () {
        const chartData = chartDataSets[this.value];
        studentDepartmentChart.data.labels = chartData.labels;
        studentDepartmentChart.data.datasets[0].data = chartData.data;
        studentDepartmentChart.options.plugins.title.text = chartData.title;
        studentDepartmentChart.update();
    });

    // Gender Doughnut Chart
    const genderCtx = document.getElementById('genderChart').getContext('2d');
    const genderChart = new Chart(genderCtx, {
        type: 'doughnut',
        data: {
            labels: ['Male','Female'],
            datasets: [{
                data: [{{ $genderCounts['Male'] ?? 0 }}, {{ $genderCounts['Female'] ?? 0 }}],
                backgroundColor: ['rgba(54, 162, 235, 0.7)','rgba(255, 99, 132, 0.7)'],
                borderColor: ['rgba(54, 162, 235, 1)','rgba(255, 99, 132, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 1200, easing: 'easeOutBounce' },
            plugins: {
                legend: { position: 'bottom' },
                title: { display: true, text: 'Student Gender Distribution' }
            }
        }
    });
</script>
@endsection
