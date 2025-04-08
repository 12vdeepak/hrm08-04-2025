<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTracker extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'activity_time',
        'activity_type',
        'start_time',
        'end_time',
    ];
}
