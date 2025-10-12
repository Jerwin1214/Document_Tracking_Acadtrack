@extends('pages.students.student-content')

@section('content')
<div class="container py-5">

    <!-- Greeting Section -->
    <div class="text-center mb-5">
        <div class="d-inline-block position-relative">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->first_name.' '.$student->last_name) }}&background=0D8ABC&color=fff&size=120"
                 alt="Avatar" class="rounded-circle shadow-lg mb-3">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                Online
            </span>
        </div>

        @php
            $hour = now()->format('H');
            $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');
        @endphp

        <h2 class="fw-bold mt-3">🎓 {{ $greeting }}, {{ $student->first_name }}!</h2>
        <p class="text-muted fs-6">Class: <span class="fw-semibold">{{ $student->class->name ?? 'Not Assigned' }}</span></p>

        <blockquote class="blockquote mx-auto mt-3" style="max-width: 600px;">
            <p class="fst-italic text-primary">"{{ $quote }}"</p>
        </blockquote>
    </div>

    <!-- Quick Stats Section -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm p-4 text-center hover-scale gradient-primary text-white">
                <div class="mb-2 display-4"><i class="bi bi-journal-text"></i></div>
                <h6 class="text-uppercase">Total Subjects</h6>
                <h2 class="fw-bold">{{ $totalSubjects }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm p-4 text-center hover-scale gradient-success text-white">
                <div class="mb-2 display-4"><i class="bi bi-check-circle"></i></div>
                <h6 class="text-uppercase">Completed Tasks</h6>
                <h2 class="fw-bold">{{ $completedTasks ?? 0 }}</h2>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card rounded-4 shadow-sm p-4 text-center hover-scale gradient-warning text-white">
                <div class="mb-2 display-4"><i class="bi bi-bell"></i></div>
                <h6 class="text-uppercase">Announcements</h6>
                <h2 class="fw-bold">{{ $announcements->count() }}</h2>
            </div>
        </div>
    </div>

    <!-- Announcements Section -->
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-4">📢 Latest Announcements</h5>
            @if($announcements->count())
                <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                    @foreach($announcements as $announcement)
                        <div class="list-group-item d-flex justify-content-between align-items-start rounded-3 mb-2 hover-scale shadow-sm">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $announcement->title }}</h6>
                                <small class="text-muted">{{ $announcement->content }}</small>
                            </div>
                            <span class="badge bg-secondary rounded-pill align-self-start">
                                {{ $announcement->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted text-center">No announcements at the moment.</p>
            @endif
        </div>
    </div>

</div>

<style>
    /* Hover and scale effects */
    .hover-scale {
        transition: transform 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }

    /* Gradient cards */
    .gradient-primary { background: linear-gradient(135deg, #0D8ABC, #0D6E99); }
    .gradient-success { background: linear-gradient(135deg, #28a745, #218838); }
    .gradient-warning { background: linear-gradient(135deg, #ffc107, #e0a800); }

    /* Scrollbar styling for announcements */
    .list-group-flush::-webkit-scrollbar {
        width: 8px;
    }
    .list-group-flush::-webkit-scrollbar-thumb {
        background: rgba(0,0,0,0.2);
        border-radius: 4px;
    }
</style>
@endsection
