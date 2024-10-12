<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Guardian;
use App\Models\Classes;
use App\Models\SubjectStream;
use App\Models\User;

class Student extends Model
{
    use HasFactory;

    //    protected $guarded = [];
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'nic',
        'dob',
        'index_no',
        'user_id',
        'guardian_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class, 'class_student', 'student_id', 'class_id');
    }

    // In Class model
    public function subjectStream()
    {
        return $this->belongsTo(SubjectStream::class);
    }
}
