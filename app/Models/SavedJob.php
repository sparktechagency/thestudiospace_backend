<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    protected $fillable = [
        'user_id',        // User who saved the job
        'job_post_id',    // Job post that is saved
        'status',         // Status of the saved job (whether it's saved or not)
    ];

    // Define the relationship to the JobPost model
    public function jobPost()
    {
        return $this->belongsTo(JobPost::class, 'job_post_id');
    }
}
