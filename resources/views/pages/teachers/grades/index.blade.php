@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">📘 My Subjects</h2>

    {{-- Subjects List --}}
    <div class="row">
        @forelse($subjects as $subject)
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <h5 class="card-title text-primary fw-bold">{{ $subject->name }}</h5>

                        @php
    $students = $subject->students ?? collect();
@endphp

<p class="text-muted mb-2">
    Students in Class: <strong>{{ $students->count() }}</strong>
</p>

@if($students->count() > 0)
<button type="button" class="btn btn-outline-primary w-100 view-subject-students"
        data-subject-id="{{ $subject->id }}"
        data-subject-name="{{ $subject->name }}"
        data-students='@json($students)'>
    View Students
</button>
@else
<button class="btn btn-outline-secondary w-100" disabled>No Students in Class</button>
@endif

                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="text-muted">🙁 You don’t have any assigned subjects yet.</p>
            </div>
        @endforelse
    </div>

    {{-- Students List Section --}}
    <div id="studentsSection" class="d-none mt-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold">
                👩‍🎓 Students for <span id="subjectName"></span>
            </h2>
            <p class="text-muted">Grade your students for this subject</p>
        </div>

        {{-- Search --}}
        <div class="mb-3">
            <input type="text" id="studentSearch" class="form-control shadow-sm rounded-pill"
                   placeholder="Search by LRN, Name, or Gender...">
        </div>

        {{-- Students Table --}}
        <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
            <table class="table table-hover align-middle mb-0 text-center" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>LRN</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Age</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5" class="text-muted">Select a subject to view students.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.querySelectorAll('.view-subject-students').forEach(button => {
    button.addEventListener('click', function () {
        const subjectName = this.getAttribute('data-subject-name');
        const students = JSON.parse(this.getAttribute('data-students'));

        document.getElementById('subjectName').textContent = subjectName;
        const section = document.getElementById('studentsSection');
        section.classList.remove('d-none');
        section.scrollIntoView({ behavior: 'smooth' });

        const tbody = document.querySelector('#studentsTable tbody');
        tbody.innerHTML = '';

        if(students.length > 0) {
            students.forEach(student => {
                tbody.innerHTML += `
                    <tr>
                        <td data-label="LRN">${student.lrn}</td>
                        <td data-label="Name">${student.first_name} ${student.middle_name ?? ''} ${student.last_name}</td>
                        <td data-label="Gender">${student.gender}</td>
                        <td data-label="Age">${student.age ?? ''}</td>
                        <td data-label="Actions">
                            <a href="/teacher/grades/student/${student.id}/${student.subject_id ?? ''}"
                               class="btn btn-info btn-sm px-3 py-1">
                                <i class="fas fa-pen"></i> Grade
                            </a>
                        </td>
                    </tr>`;
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-muted">No students in this class.</td></tr>';
        }
    });
});

// Search filter
document.getElementById('studentSearch')?.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#studentsTable tbody tr');

    rows.forEach(row => {
        const lrn = row.cells[0]?.textContent.toLowerCase() || '';
        const name = row.cells[1]?.textContent.toLowerCase() || '';
        const gender = row.cells[2]?.textContent.toLowerCase() || '';
        row.style.display = (lrn.includes(filter) || name.includes(filter) || gender.includes(filter)) ? '' : 'none';
    });
});
</script>
@endsection
