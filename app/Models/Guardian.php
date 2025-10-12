<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

//    protected $guarded = [];
 protected $fillable = [
    'student_id',
    'first_name',
    'middle_initial',
    'last_name',
    'address',
    'phone_number', // ✅ correct
];



    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
