<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
     protected $fillable = [
        'user_id',
        'comment_id',
        'content',
        'image',
        'emoji',
    ];
    public function likes()
    {
        return $this->hasMany(ReplyLike::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
