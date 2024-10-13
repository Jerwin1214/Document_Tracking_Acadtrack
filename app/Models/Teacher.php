<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Subject;
use \App\Models\User;
use \App\Models\Classes;

class Teacher extends Model
{
    use HasFactory;

    //    protected $guarded = [];
    protected $fillable = [
        'salutation',
        'initials',
        'first_name',
        'last_name',
        'nic',
        'dob',
        'user_id',
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

    // for user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // for subjects
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    // for classes
    public function classes()
    {
        return $this->hasMany(Classes::class);
    }

    // for announcements
    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
}
