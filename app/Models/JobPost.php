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
        'budget',
        'status',
    ];
    protected $casts = [
        'required_skills' => 'array',
    ];

    public function art()
    {
        return $this->belongsTo(Art::class, 'art_id');
    }
}
