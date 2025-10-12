<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementaryGrade extends Model
{
    use HasFactory;

    protected $table = 'elementary_grades';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'grade_level',
        'filipino',
        'english',
        'reading_and_literacy',
        'mathematics',
        'science',
        'makabansa',
        'ap',
        'mapeh',
        'epp',
        'tle',
        'esp',
        'gmrc',
        'grading_quarter',
        // 'school_year',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
