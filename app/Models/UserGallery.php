<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGallery extends Model
{
     protected $fillable = [
        'user_id',
        'files',  
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cast 'files' to an array so you can work with it as an array directly
    protected $casts = [
        'files' => 'array',  // Automatically cast the JSON data to an array
    ];
}
