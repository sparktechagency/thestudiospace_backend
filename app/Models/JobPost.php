<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
   protected $fillable = [
        'user_id',
        'job_title',
        'art_id',
        'job_type',
        'location',
        'application_deadline',
        'job_description',
        'required_skills',
        'start_budget',
        'end_budget',
        'status',
    ];
    protected $casts = [
        'required_skills' => 'array',
    ];

    public function art()
    {
        return $this->belongsTo(Art::class, 'art_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }
}
