<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessProfileFollow extends Model
{
    protected $fillable = [
        'user_id',
        'business_profile_id',
        'status'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function businessProfile()
    {
        return $this->belongsTo(User::class, 'business_profile_id'); 
    }
}
