<?php

namespace App\Services\Job;

use App\Models\JobPost;
use App\Models\Position;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplyNowService
{
   use ResponseHelper;

   public function applyNow($data, $job_post_id)
    {
        $job_post = JobPost::find($job_post_id);
        if (!$job_post) {
            return $this->errorResponse("Job post not found.", 404);
        }
        $user = Auth::user();
        $existing_application = Position::where('job_post_id', $job_post_id)
                                        ->where('applicant_id', $user->id)
                                        ->first();
        if ($existing_application) {
            return $this->errorResponse("You have already applied for this job.", 400);
        }
        if (isset($data['resume']) && $data['resume']) {
            $existingInfo = Position::where('applicant_id', $user->id)->first();
            if ($existingInfo && $existingInfo->resume && Storage::disk('public')->exists($existingInfo->resume)) {
                Storage::disk('public')->delete($existingInfo->resume);
            }
            $path = $data['resume']->store('resumes', 'public');
            $data['resume'] = 'storage/' . $path;
        }
        $position = Position::create([
            'job_post_id' => $job_post_id,
            'applicant_id' => $user->id,
            'resume' => $data['resume'] ?? null,
            'cover_letter' => $data['cover_letter'] ?? null,
        ]);
        if ($position) {
            return $this->successResponse($position, "You have successfully applied for the job.");
        }
        return $this->errorResponse("There was an issue with your application. Please try again later.");
    }
}
