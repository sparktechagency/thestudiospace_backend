<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupComment extends Model
{
   protected $fillable = [
        'group_id',
        'group_post_id',
        'user_id',
        'content',
        'image',
        'emoji',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
