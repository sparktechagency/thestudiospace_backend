<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
      protected $fillable = [
        'job_post_id',
        'applicant_id',
        'resume',
        'cover_letter',
    ];
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class,'job_post_id');
    }
    public function applicant()
    {
        return $this->belongsTo(User::class,'applicant_id');
    }
}
