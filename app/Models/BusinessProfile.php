<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfile extends Model
{
    protected $fillable = [
        'business_name',
        'art_id',
        'user_id',
        'location',
        'description',
        'website',
        'email',
        'social_links',
        'privacy_settings',
        'cover_picture',
        'avatar',
    ];

    // If you want to cast 'social_links' as an array
    protected $casts = [
        'social_links' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function art()
    {
        return $this->belongsTo(Art::class);
    }
}
