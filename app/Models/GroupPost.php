<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
   protected $fillable = [
        'group_id',
        'user_id',
        'content',
        'photos',
        'video',
        'document',
    ];

    // Optional: cast JSON columns to array automatically
    protected $casts = [
        'photos' => 'array',
        'video' => 'array',
    ];
}
