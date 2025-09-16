<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
     protected $fillable = [
        'user_id',
        'cover_picture',
        'job_title',
        'company_name',
        'location',
        'phone_number',
        'address',
        'website',
        'bio',
        'profile_visibility',
    ];

    // Optional: relationship to user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
