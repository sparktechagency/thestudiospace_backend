<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class UserInfo extends Model
{
      protected static $logAttributes = [
        'cover_picture',
        'job_title',
        'company_name',
        'location',
        'phone_number',
        'address',
        'website',
        'bio',
        'profile_visibility',
    ];

    protected static $logOnlyDirty = true;

    public function getActivitylogOptions(): \Spatie\Activitylog\Contracts\ActivitylogOptions
    {
        return \Spatie\Activitylog\ActivitylogOptions::create()
            ->logOnly(['cover_picture', 'job_title', 'company_name', 'location', 'phone_number', 'address', 'website', 'bio', 'profile_visibility']);
    }

    // Custom description for activity log events
    public function getDescriptionForEvent(string $eventName): string
    {
        switch ($eventName) {
            case 'updated':
                return "UserInfo updated: {$this->user->name}'s profile was updated";
            default:
                return "UserInfo profile viewed";
        }
    }

     protected $fillable = [
        'user_id',
        'cover_picture',
        'job_title',
        'company_name',
        'location',
        'phone_number',
        'address',
        'website',
        'bio',
        'profile_visibility',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
