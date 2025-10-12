@extends('pages.teachers.teacher-content')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">🎓 Senior High Grading</h2>
    <h5>{{ $student->first_name }} {{ $student->last_name }}</h5>

    <form action="{{ route('teacher.grades.store') }}" method="POST">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

        <div class="row">
            @foreach([
                'oral_communication','reading_and_writing_skills','komunikasyon_at_pananaliksik',
                'pagbasa_at_pagsusuri','general_mathematics','earth_and_life_science',
                'personal_development','understanding_culture_society','physical_education_and_health',
                'empowerment_technologies','practical_research_1','lit_21st_century',
                'contemporary_phil_arts','media_and_info_lit','statistics_and_probability',
                'physical_science','philosophy','practical_research_2','filipino_sa_piling_larangan',
                'entrepreneurship','inquiries_investigations_immersion'
            ] as $field)
                <div class="col-md-4 mb-3">
                    <label class="form-label text-capitalize">{{ str_replace('_',' ', $field) }}</label>
                    <input type="number" name="grades[{{ $field }}]" class="form-control" min="0" max="100">
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-success">Save Grades</button>
    </form>
</div>
@endsection
