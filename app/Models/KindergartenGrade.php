<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KindergartenGrade extends Model
{
    use HasFactory;

    // 👇 Fix table mapping
    protected $table = 'kinder_grades';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'class_id',
        'language_literacy',
        'mathematics',
        'science_social',
        'social_emotional',
        'creative_arts_pe',
        'quarter',
        'school_year',
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
