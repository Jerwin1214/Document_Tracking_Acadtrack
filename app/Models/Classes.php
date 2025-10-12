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
     * One-to-One / Many-to-One: Class Adviser
     */
    public function adviser()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Many-to-Many: Class ↔ Subject Teachers
     */
    public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_id');
}


    /**
     * Many-to-Many: Class ↔ Subjects
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
                    ->withTimestamps();
    }

    /**
     * One-to-Many: Class ↔ Grades
     */
    public function grades()
    {
        return $this->hasMany(Grade::class, 'class_id');
    }

    /**
     * Many-to-One: Class ↔ Subject Stream (e.g., ABM, STEM)
     */
    public function subjectStream()
    {
        return $this->belongsTo(SubjectStream::class, 'subject_stream_id');
    }

    /**
     * Scope: Filter classes by teacher and year
     */
    public function scopeForTeacher($query, $teacherId, $year)
    {
        return $query->where('teacher_id', $teacherId)
                     ->where('year', $year);
    }

    /**
     * Accessor: Full class name
     */
    public function getFullNameAttribute()
    {
        return "{$this->department} {$this->year_level} - {$this->section}";
    }
}
