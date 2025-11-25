<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLike extends Model
{
   protected $fillable = [
        'group_id',
        'group_post_id',
        'type',
        'user_id',
        'status',
    ];
    public function post()
    {
        return $this->belongsTo(GroupPost::class, 'group_post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
