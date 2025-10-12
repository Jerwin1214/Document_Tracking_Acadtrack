<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Subject;
use App\Models\User;
use App\Models\Classes;
use App\Models\Announcement;
use App\Models\Student;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'salutation',
        'middle_name',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'user_id',
        'address',
    ];

    protected $casts = [
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Teacher belongs to a user account
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Teacher can teach multiple subjects
     */
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'subject_teacher', // pivot table
            'teacher_id',      // foreign key on pivot for teacher
            'subject_id'       // foreign key on pivot for subject
        );
    }

    /**
     * One teacher has many classes
     */
    public function classes()
    {
        return $this->hasMany(Classes::class, 'teacher_id', 'id');
    }

    /**
     * Teacher can post multiple announcements
     */
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'teacher_id', 'id');
    }

    /**
     * Teacher's students through classes
     */
    public function studentsThroughClasses()
    {
        return $this->hasManyThrough(
            Student::class,
            Classes::class,
            'teacher_id',   // Foreign key on Classes table
            'class_id',     // Foreign key on Students table (pivoted via class_student)
            'id',           // Local key on Teacher table
            'id'            // Local key on Classes table
        );
    }

    /**
     * 🔗 NEW: Teacher <-> Student many-to-many relationship
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_teacher', 'teacher_id', 'student_id')
                    ->withTimestamps();
    }


    /**
     * Accessor for full name
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->salutation} {$this->first_name} {$this->last_name}");
    }
}
