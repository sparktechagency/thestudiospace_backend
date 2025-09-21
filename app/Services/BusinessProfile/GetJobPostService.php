<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class GetJobPostService
{
   use ResponseHelper;

   public function getJobPost()
   {
      $job_post = JobPost::orderBy('id','desc')->paginate(20);
        if (isset($job_post['required_skills']) && is_array($job_post['required_skills'])) {
            $job_post['required_skills'] = json_encode($job_post['required_skills']);
        }
      return $this->successResponse($job_post,"Job posts fetched successfully.");
   }
}
