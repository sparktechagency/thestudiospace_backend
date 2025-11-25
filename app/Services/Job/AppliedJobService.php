<?php

namespace App\Services\Job;

use App\Models\Position;
use App\Models\SavedJob;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class AppliedJobService
{
   use ResponseHelper;
    public function appliedJobs()
    {
        $positions = Position::with(['jobPost','applicant:id,name,email,avatar,phone_number'])
        ->orderBy('id', 'desc')->paginate(20);
        foreach ($positions as $position) {
            if (isset($position->jobPost->required_skills) && is_string($position->jobPost->required_skills)) {
                $position->jobPost->required_skills = json_decode($position->jobPost->required_skills, true);
            }
            $position->is_saved = $this->getSavedJobStatus($position->job_post_id);
        }
        return $this->successResponse($positions, "Applied jobs fetched successfully.");
    }
    private function getSavedJobStatus($job_post_id)
    {
        $user_id = Auth::id();
        $savedJob = SavedJob::where('user_id', $user_id)
                            ->where('job_post_id', $job_post_id)
                            ->first();
        return $savedJob ? $savedJob->status : false;
    }
}
