<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectStream extends Model
{
    use HasFactory;

    protected $fillable = ['stream_name', 'id', 'stream_code', 'stream_description'];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_stream_subject');
    }


    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function classes()
    {
        return $this->hasMany(Classes::class);
    }
}
