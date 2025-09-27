<?php

namespace App\Services\Job;

use App\Models\SavedJob;
use App\Traits\ResponseHelper;

class GetSavedJobService
{
    use ResponseHelper;

    public function savedJobs()
    {
        $savedJobs = SavedJob::with(['jobPost'])->where('status', true)
                            ->orderBy('updated_at', 'desc')
                            ->paginate(20);
        return $this->successResponse($savedJobs, "Saved jobs fetched successfully.");
    }
}
