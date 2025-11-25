<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
     protected $fillable = [
        'name',
        'user_id',
        'art_id',
        'description',
        'location',
        'banner_image',
        'logo_image',
        'group_type',
        'allow_post',
        'admin_approval',
    ];
    public function members()
    {
        return $this->hasMany(GroupMember::class, 'group_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function art()
    {
        return $this->belongsTo(Art::class, 'art_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
