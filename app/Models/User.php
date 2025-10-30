<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'email',
        'password',
        'role_id',
        'is_active',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Use user_id as login field
    public function getAuthIdentifierName()
    {
        return 'user_id';
    }

    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
}
