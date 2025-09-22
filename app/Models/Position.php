<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
      protected $fillable = [
        'job_post_id',
        'applicant_id',
        'resume',
        'cover_letter',
    ];
}
