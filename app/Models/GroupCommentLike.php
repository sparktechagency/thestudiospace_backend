<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCommentLike extends Model
{
    protected $fillable = [
        'group_id',
        'group_post_id',
        'group_comment_id',
        'user_id',
        'status',
    ];
}
