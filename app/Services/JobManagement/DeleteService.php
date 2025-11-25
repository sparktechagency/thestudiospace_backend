<?php

namespace App\Services\JobManagement;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class DeleteService
{
    use ResponseHelper;

    public function delete($id)
    {
        $job = JobPost::find($id);
        if (!$job) {
            return $this->errorResponse('Job not found.');
        }
        $job->delete();
        return $this->successResponse([], 'Job deleted successfully.');
    }
}
