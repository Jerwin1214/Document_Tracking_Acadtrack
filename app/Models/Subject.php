<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    /**
     * Many-to-Many: Subject ↔ Teachers
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'subject_teacher', 'subject_id', 'teacher_id');
    }

       public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Many-to-Many: Subject ↔ Classes
     */
 // App\Models\Subject.php
public function classes()
{
    return $this->hasManyThrough(
        \App\Models\Classes::class,  // Target model
        \App\Models\Student::class,  // Through model
        'department',                // Foreign key on students table
        'department',                // Foreign key on classes table
        'id',                        // Local key on subjects table
        'department'                 // Local key on students table
    );
}

public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id'); // make sure 'class_id' exists in subjects table
    }
    /**
     * Many-to-Many: Subject ↔ Students
     */
 public function students()
    {
        return $this->belongsToMany(Student::class, 'grades')
                    ->withPivot('quarter', 'grade', 'remarks', 'teacher_id');
    }


}
