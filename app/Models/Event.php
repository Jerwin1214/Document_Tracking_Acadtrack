<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = ['title', 'start', 'end'];

    // Optional: tell Laravel these are dates
    protected $dates = ['start', 'end'];
}
