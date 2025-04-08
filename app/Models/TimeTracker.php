<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTracker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'job_id',
        'work_title',
        'description',
        'work_time',
        'start_time',
        'end_time',
        'work_date',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function job(){
        return $this->belongsTo(JobName::class,'job_id');
    }

    public function project(){
        return $this->belongsTo(ProjectName::class,'project_id');
    }
}
