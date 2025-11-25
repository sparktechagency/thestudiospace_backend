<?php

namespace App\Services\JobManagement;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class RejectService
{
    use ResponseHelper;

    public function reject($id)
    {
        // Find the job
        $job = JobPost::find($id);

        if (!$job) {
            return $this->errorResponse('Job not found.', 404);
        }

        // Update status to 'rejected'
        $job->update(['status' => 'rejected']);

        return $this->successResponse($job, 'Job rejected successfully.');
    }
}
