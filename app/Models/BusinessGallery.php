<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessGallery extends Model
{
    protected $fillable = [
        'user_id',
        'files',
    ];

    // Automatically cast the `files` field to an array
    protected $casts = [
        'files' => 'array',
    ];

    // Define relationship with the user (optional if you need it)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
