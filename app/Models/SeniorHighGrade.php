<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeniorHighGrade  extends Model
{
    use HasFactory;

    protected $table = 'senior_high_grades';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'grade_level',
        'strand',
        'oral_communication',
        'reading_and_writing_skills',
        'komunikasyon_at_pananaliksik',
        'pagbasa_at_pagsusuri',
        'general_mathematics',
        'earth_and_life_science',
        'personal_development',
        'understanding_culture_society',
        'physical_education_and_health',
        'empowerment_technologies',
        'practical_research_1',
        'lit_21st_century',
        'contemporary_phil_arts',
        'media_and_info_lit',
        'statistics_and_probability',
        'physical_science',
        'philosophy',
        'practical_research_2',
        'filipino_sa_piling_larangan',
        'entrepreneurship',
        'inquiries_investigations_immersion',
        'grading_quarter',
        'school_year',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
