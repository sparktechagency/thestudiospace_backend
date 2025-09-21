<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class JobPostService
{
    use ResponseHelper;

    public function jobPost($data)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->errorResponse("User not found.");
        }
        $data['user_id'] = $user->id;
        $jobPost = JobPost::create($data);
        return $this->successResponse($jobPost, "Job post created successfully.");
    }
}
