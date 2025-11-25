<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasApiTokens;
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone_number',
        'google_id',
        'role',
        'user_type',
        'is_banned',
        'is_online',
        'fcm_token',
        'is_business',
        'is_post'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }

    public function connections()
    {
        return $this->hasMany(Conection::class, 'user_id');
    }

    public function connectedWith()
    {
        return $this->hasMany(Conection::class, 'connection_id');
    }
}
