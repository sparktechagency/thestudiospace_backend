<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
     protected $fillable = [
        'user_id',
        'job_title',
        'company',
        'location',
        'start_date',
        'end_date',
        'current',
        'description',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
