<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'teacher_id',
        'quarter',
        'grade',
        'remarks',
    ];

    // A grade belongs to one student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // A grade belongs to one subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // A grade belongs to one teacher
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
