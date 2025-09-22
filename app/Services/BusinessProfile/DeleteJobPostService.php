<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class DeleteJobPostService
{
    use ResponseHelper;

   public function deleteJobPost($job_post_id)
    {
        $jobPost = JobPost::find($job_post_id);
        if (!$jobPost) {
            return $this->errorResponse("Job post not found.");
        }
        $jobPost->delete();
        return $this->successResponse([], "Job post deleted successfully.");
    }
}
