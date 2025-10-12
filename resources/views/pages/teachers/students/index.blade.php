@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">

    {{-- Page Title --}}
    <div class="text-center mb-4">
        <h2 class="fw-bold">👩‍🏫 My Classes</h2>
        <p class="text-muted">Select a class to view its students</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    {{-- Teacher's Assigned Classes --}}
    <div class="row justify-content-center mb-5">
        @forelse($classes as $class)
            <div class="col-md-3 col-sm-6 mb-3">
                <button type="button"
        class="btn btn-primary w-100 shadow-sm rounded-3 py-3 view-class"
        data-url="{{ route('teacher.classes.students.ajax', $class) }}"
        data-class="{{ $class->name }}">
    <i class="fas fa-users me-2"></i> {{ $class->name }}
</button>

            </div>
        @empty
            <div class="col-12 text-center text-muted">
                No classes assigned yet.
            </div>
        @endforelse
    </div>

    {{-- Student List Section (hidden by default) --}}
    <div id="studentsSection" class="d-none">
        <div class="text-center mb-4">
            <h2 class="fw-bold">
                👩‍🎓 Students of <span id="className"></span>
            </h2>
            <p class="text-muted">Overview of students enrolled in this class</p>
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
                        <td colspan="5" class="text-muted">Select a class to view students.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Script --}}
<script>
document.querySelectorAll('.view-class').forEach(button => {
    button.addEventListener('click', async function () {
        const className = this.getAttribute('data-class');
        const url = this.getAttribute('data-url');

        document.getElementById('className').textContent = className;
        document.getElementById('studentsSection').classList.remove('d-none');
        document.getElementById('studentsSection').scrollIntoView({ behavior: 'smooth' });

        const tbody = document.querySelector('#studentsTable tbody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-muted">Loading...</td></tr>';

        try {
            const res = await fetch(url);
            const students = await res.json();

            if (students.length > 0) {
                tbody.innerHTML = '';
                students.forEach(student => {
                    tbody.innerHTML += `
                        <tr>
                            <td data-label="LRN">${student.lrn}</td>
                            <td data-label="Name">${student.first_name} ${student.middle_name ?? ''} ${student.last_name}</td>
                            <td data-label="Gender">${student.gender}</td>
                            <td data-label="Age">${student.age ?? ''}</td>
                            <td data-label="Actions">
                                <a href="/teacher/students/${student.id}"
                                   class="btn btn-info btn-sm px-3 py-1">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>`;
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="5" class="text-muted">No students in this class.</td></tr>';
            }
        } catch (error) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-danger">Error loading students.</td></tr>';
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
