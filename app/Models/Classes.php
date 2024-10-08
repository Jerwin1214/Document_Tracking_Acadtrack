<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class Classes extends Model
{

    protected $table = 'classes';

//    protected $guarded = [];
    protected $fillable = [
        'grade_id',
        'teacher_id',
        'subject_stream_id',
        'name',
        'year',
    ];

    // for students
    public function students()
    {
        return $this->belongsToMany(Student::class, 'class_student', 'class_id', 'student_id');
    }

    // for teachers
    public function teachers()
    {
        return $this->belongsTo(Teacher::class);
    }

    // for subject
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // for grades
    public function grades()
    {
        return $this->belongsTo(Grade::class);
    }
}
