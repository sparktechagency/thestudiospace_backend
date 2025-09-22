<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class UpdateJobPostService
{
    use ResponseHelper;

   public function updateJobPost($data, $job_post_id)
    {
        $jobPost = JobPost::find($job_post_id);
        if (!$jobPost) {
            return $this->errorResponse("Job post not found.", 404);
        }
        if (isset($data['required_skills']) && is_array($data['required_skills'])) {
            $data['required_skills'] = json_encode($data['required_skills']);
        }
        $jobPost->update($data);
        return $this->successResponse($jobPost, "Job post updated successfully.");
    }
}
