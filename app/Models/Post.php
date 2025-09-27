<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'photos',
        'video',
        'document',
        'privacy',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
      protected $casts = [
        'photos' => 'array',
        'video' => 'array',
    ];
    public function getPhotosAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    public function setPhotosAttribute($value)
    {
        $this->attributes['photos'] = json_encode($value);
    }
    public function getVideoAttribute($value)
    {
        return $value ? json_decode($value, true) : [];
    }
    public function setVideoAttribute($value)
    {
        $this->attributes['video'] = json_encode($value);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function shares()
    {
        return $this->hasMany(Share::class);
    }
    
}
