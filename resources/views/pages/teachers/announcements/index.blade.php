@extends('pages.teachers.teacher-content');
<!-- Slotted content -->
@section('content')
<h2>All Announcement</h2>
@if (session('success'))
<x-popup-message type="success" :message="session('success')" />
@endif

@if (session('warning'))
<x-popup-message type="warning" :message="session('warning')" />
@endif

@if (session('error'))
<x-popup-message type="error" :message="session('error')" />
@endif
<!--  -->

<!-- Today's Announcements -->
<div class="container mt-3">
    @if($todayAnnouncements->isNotEmpty())
    <h4>Today</h4>
    <div class="list-group mb-4">
        @foreach($todayAnnouncements as $announcement)
        <div class="list-group-item">
            <div class="d-flex mb-2">
                <div class="me-auto p-2">
                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                </div>
                <div class="p-1">
                    <a href="" class="btn btn-outline-primary btn-sm">Show</a>
                </div>
                <div class="p-1">
                    <a href="" class="btn btn-outline-warning btn-sm">Edit</a>
                </div>
                <div class="p-1">
                    <form action="" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>
            <small>{{ $announcement->created_at->format('h:i A') }}</small>
            <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Yesterday's Announcements -->
    @if($yesterdayAnnouncements->isNotEmpty())
    <h3>Yesterday</h3>
    <div class="list-group mb-4">
        @foreach($yesterdayAnnouncements as $announcement)
        <div class="list-group-item">
            <div class="d-flex mb-2">
                <div class="me-auto p-2">
                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                </div>
                <div class="p-2">
                    <a href="" class="btn btn-outline-warning btn-sm">Edit</a>
                </div>
                <div class="p-2">
                    <form action="" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>

            <small>{{ $announcement->created_at->format('h:i A') }}</small>
            <p class="mb-1">{{ $announcement->content }}</p>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Older Announcements -->
    @if($otherAnnouncements->isNotEmpty())
    <h3>Older Announcements</h3>
    <div class="list-group mb-4">
        @foreach($otherAnnouncements as $announcement)
        <div class="list-group-item">
            <div class="d-flex mb-2">
                <div class="me-auto p-2">
                    <h5 class="mb-1">{{ $announcement->title }}</h5>
                </div>
                <div class="p-2">
                    <a href="" class="btn btn-outline-warning btn-sm">Edit</a>
                </div>
                <div class="p-2">
                    <form action="" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </div>

            <small>{{ $announcement->created_at->format('M d, Y h:i A') }}</small>
            <p class="mb-1">{{ $announcement->content }}</p>
        </div>
        @endforeach
    </div>
    @endif
</div>
<script>
    $(document).ready(function() {
        // set page title
        $(document).prop('title', 'All Announcement | Student Management System');
    });
</script>

@endsection