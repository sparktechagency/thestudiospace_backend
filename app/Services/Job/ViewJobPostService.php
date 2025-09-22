<?php

namespace App\Services\Job;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class ViewJobPostService
{
  use ResponseHelper;
    public function viewJobPost($job_post_id)
    {
        $job_post = JobPost::with(['user:id,name,email,avatar', 'art:id,name', 'userInfo'])
                           ->find($job_post_id);
        if (!$job_post) {
            return $this->errorResponse("Job post not found.", 404);
        }
        if (isset($job_post->required_skills) && is_string($job_post->required_skills)) {
            $job_post->required_skills = json_decode($job_post->required_skills, true);
        }
            activity()
            ->performedOn(JobPost::find($job_post_id))
            ->causedBy(auth()->user())
            ->log('Viewed job post');
        return $this->successResponse($job_post, "Job post fetched successfully.");
    }
}
