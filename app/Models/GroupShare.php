<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupShare extends Model
{
     protected $fillable = [
        'group_id',
        'group_post_id',
        'user_id',
    ];
}
