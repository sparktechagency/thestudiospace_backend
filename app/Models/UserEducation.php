<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
      protected $fillable = [
        'user_id',
        'school_or_university',
        'degree',
        'start_date',
        'end_date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
