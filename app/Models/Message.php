<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
     protected $fillable = [
        'chat_id',
        'sender_id',
        'message',
        'attachment',
        'is_read',
    ];
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }
}
