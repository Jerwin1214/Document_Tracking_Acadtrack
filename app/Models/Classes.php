<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;

class Classes extends Model
{

    protected $table = 'class';

    protected $guared = [];

    // for students
    public function students()
    {
        return $this->belongsToMany(Student::class);
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
