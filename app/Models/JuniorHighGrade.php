<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuniorHighGrade extends Model
{
    use HasFactory;

    protected $table = 'junior_high_grades';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'grade_level',
        'filipino',
        'english',
        'mathematics',
        'science',
        'ap',
        'mapeh',
        'tle',
        'values_education',
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
