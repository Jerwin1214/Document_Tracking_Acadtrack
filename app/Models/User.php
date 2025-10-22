<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 */

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
     * Relationship to Role model (optional).
     * If you don’t have a Role model, you can safely delete this.
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\UserRole::class, 'role_id');
    }

    /**
     * Convenience accessor for role as string.
     * This allows you to call $user->role (string).
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

    public function student()
    {
        return $this->hasOne(\App\Models\Student::class, 'user_id');
    }
}
