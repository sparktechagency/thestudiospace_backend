<?php

namespace App\Services\JobManagement;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class ApprovedService
{
    use ResponseHelper;

    public function approve($id)
    {

        $job = JobPost::find($id);

        if (!$job) {
            return $this->errorResponse('Job not found.', 404);
        }

        // Update status to 'approved'
        $job->update(['status' => 'approved']);

        return $this->successResponse($job, 'Job approved successfully.');
    }
}
