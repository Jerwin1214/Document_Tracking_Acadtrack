<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

//    protected $guarded = [];
    protected $fillable = [
        'initials',
        'first_name',
        'last_name',
        'nic',
        'phone_number',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
