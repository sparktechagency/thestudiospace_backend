<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

      public function likes()
    {
        return $this->hasMany(Like::class);
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
}
