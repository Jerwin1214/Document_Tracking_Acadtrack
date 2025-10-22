<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model', 'description'
    ];

    public function user()
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

}
