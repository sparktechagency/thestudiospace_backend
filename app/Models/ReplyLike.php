<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplyLike extends Model
{
    protected $fillable = [
        'user_id',
        'reply_id',
        'status',
    ];
}
