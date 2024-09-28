<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class);
    }
}
