<?php

namespace App\Services\Job;

use App\Models\JobPost;
use App\Models\SavedJob;
use App\Traits\ResponseHelper;

class SavedJobService
{
    use ResponseHelper;
    public function savedUnsavedJob($job_post_id)
    {
        $user_id = auth()->id();
        $job_post = JobPost::find($job_post_id);
        if (!$job_post) {
            return $this->errorResponse("Job post not found.");
        }
        $existingSavedJob = SavedJob::where('user_id', $user_id)
                                    ->where('job_post_id', $job_post_id)
                                    ->first();
        if ($existingSavedJob) {
            if ($existingSavedJob->status == true) {
                $existingSavedJob->status = false;
                $existingSavedJob->save();
                return $this->successResponse($existingSavedJob, "Job unsaved successfully.");
            }
            if ($existingSavedJob->status == false) {
                $existingSavedJob->status = true;
                $existingSavedJob->save();
                return $this->successResponse($existingSavedJob, "Job saved successfully.");
            }
        }
        $saved_job = SavedJob::create([
            'user_id' => $user_id,
            'job_post_id' => $job_post_id,
            'status' => true,
        ]);
        return $this->successResponse($saved_job, "Job saved successfully.");
    }
}
