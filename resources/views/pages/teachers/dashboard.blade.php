@extends('pages.teachers.teacher-content')

@section('content')
    {{-- Greeting Toast --}}
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

    <div class="container py-4">
        {{-- Greeting --}}
        <div class="text-center mb-5">
            <h2 class="fw-bold">👋 Welcome, {{ $teacher->salutation ?? '' }} {{ $teacher->first_name ?? '' }}!</h2>
            <p class="text-muted">Here’s a quick overview of your classes and subjects.</p>
        </div>

        {{-- Quick Stats Cards --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-primary shadow-lg border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Students</h6>
                            <h2 class="fw-bold">{{ $totalStudents }}</h2>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-success shadow-lg border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Subjects</h6>
                            <h2 class="fw-bold">{{ $totalSubjects }}</h2>
                        </div>
                        <i class="fas fa-book-open fa-2x"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-sm-6">
                <div class="card text-white bg-warning shadow-lg border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Classes</h6>
                            <h2 class="fw-bold">{{ $teacher->classes->count() ?? 0 }}</h2>
                        </div>
                        <i class="fas fa-chalkboard-teacher fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Charts: Gender & Subjects --}}
        <div class="row g-4">
            {{-- Student Gender Distribution --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 p-3">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-3">📊 Student Gender Distribution</h5>
                        <div class="chart-wrapper mx-auto" style="max-width: 400px;">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Teacher Subjects --}}
            <div class="col-md-6">
                <div class="card border-0 shadow-lg rounded-4 p-3">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-3">📊 Subjects Assigned</h5>
                        <div class="chart-wrapper mx-auto" style="max-width: 400px;">
                            <canvas id="subjectChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Gender Chart
        const ctxGender = document.getElementById('genderChart').getContext('2d');
        new Chart(ctxGender, {
            type: 'bar',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    label: 'Number of Students',
                    data: [
                        {{ $students->where('gender', 'Male')->count() }},
                        {{ $students->where('gender', 'Female')->count() }}
                    ],
                    backgroundColor: ['#007bff', '#e83e8c'],
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, stepSize: 1 } }
            }
        });

        // Subjects Chart
        const ctxSubject = document.getElementById('subjectChart').getContext('2d');
        new Chart(ctxSubject, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($teacher->subjects as $subject)
                        '{{ $subject->name }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Subjects Count',
                    data: [
                        @foreach($teacher->subjects as $subject)
                            1,
                        @endforeach
                    ],
                    backgroundColor: '#28a745',
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true, stepSize: 1 } }
            }
        });
    </script>

    <style>
        /* Hover effect on quick stats cards */
        .card.text-white:hover {
            transform: translateY(-3px);
            transition: transform 0.3s ease;
        }

        /* Chart card padding */
        .chart-wrapper { width: 100%; }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .card-body h2 { font-size: 1.5rem; }
            .card-body h6 { font-size: 0.8rem; }
        }
    </style>
@endsection
