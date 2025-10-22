<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    // Table name
    protected $table = 'classes';

    // Allow mass assignment
    protected $fillable = [
        'name',
        'year_level',
        'section',
        'department',
        'subject_stream_id',
        'year',
        'teacher_id', // keep if you want an adviser assigned directly
    ];

    /**
     * Many-to-Many: Class ↔ Students (via pivot class_student)
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id');
    }
    /**
     * Accessor: Full class name
     */
    public function getFullNameAttribute()
    {
        return "{$this->department} {$this->year_level} - {$this->section}";
    }
}
