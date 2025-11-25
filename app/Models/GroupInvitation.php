<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupInvitation extends Model
{
    protected $fillable=[
        'group_id','invitee_id','inviter_id','status'
    ];
    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function invitee()
    {
        return $this->belongsTo(User::class, 'invitee_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

}
