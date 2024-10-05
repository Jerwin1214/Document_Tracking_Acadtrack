@extends('pages.admin.admin-content')

@section('content')

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
                title: "{{session('greeting')}}"
            });
        </script>
    @endif

    <h2>Dashboard</h2>
    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card border-primary mb-3" style="max-width: 18rem;">
                <div class="card-body text-primary">
                    <h5 class="card-title" id="student_count">{{$counts->students_count}}</h5>
                    <p class="card-text">Students</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info mb-3" style="max-width: 18rem;">
                <div class="card-body text-info">
                    <h5 class="card-title">{{$counts->teachers_count}}</h5>
                    <p class="card-text" id="teacher_count">Teachers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning mb-3" style="max-width: 18rem;">
                <div class="card-body text-warning">
                    <h5 class="card-title" id="subject_count">{{$counts->subjects_count}}</h5>
                    <p class="card-text">Subjects</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // set page title
            $(document).prop('title', 'Admin Dashboard | Student Management System');
        });
    </script>
@endsection
