<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'role_id',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Tell Laravel to use `user_id` for authentication instead of email.
     */
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    /**
     * Relationship to Role model (optional).
     */
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }

    /**
     * Convenience accessor for role as string.
     */
    public function getRoleAttribute()
    {
        return match($this->role_id) {
            1 => 'admin',
            2 => 'student',
            3 => 'teacher',
            default => 'unknown',
        };
    }
}
