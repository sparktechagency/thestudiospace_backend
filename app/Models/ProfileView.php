<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileView extends Model
{
    protected $guarded = [];
     public function viewer()
    {
        return $this->belongsTo(User::class, 'viewer_id');
    }

    /**
     * User whose profile was viewed
     */
    public function visited()
    {
        return $this->belongsTo(User::class, 'visited_id');
    }
}
