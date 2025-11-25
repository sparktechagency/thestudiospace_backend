<?php

namespace App\Services\Job;

use App\Models\JobPost;
use App\Models\Position;
use App\Models\User;
use App\Services\Notification\NotificationService;
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

        // Check existing application
        $existing_application = Position::where('job_post_id', $job_post_id)
                                        ->where('applicant_id', $user->id)
                                        ->first();

        if ($existing_application) {
            return $this->errorResponse("You have already applied for this job.", 400);
        }

        // Upload Resume
        if (isset($data['resume']) && $data['resume']) {

            $existingInfo = Position::where('applicant_id', $user->id)->first();

            if ($existingInfo && $existingInfo->resume && Storage::disk('public')->exists($existingInfo->resume)) {
                Storage::disk('public')->delete($existingInfo->resume);
            }

            $path = $data['resume']->store('resumes', 'public');
            $data['resume'] = 'storage/' . $path;
        }

        // Save application
        $position = Position::create([
            'job_post_id'   => $job_post_id,
            'applicant_id'  => $user->id,
            'resume'        => $data['resume'] ?? null,
            'cover_letter'  => $data['cover_letter'] ?? null,
        ]);


        // -------------------------------------------------------
        //   SEND NOTIFICATION TO ADMINS ONLY (not job owner)
        // -------------------------------------------------------
        $admins = User::where('role', 'ADMIN')->get();

        if ($admins->count() > 0) {
            $notificationService = new NotificationService();

            foreach ($admins as $admin) {
                $notificationService->send($admin, [
                    'title' => "New Job Application",
                    'message' => $user->name . " applied for: " . $job_post->title,
                    'type' => "job_application",
                    'job_post_id' => $job_post_id,
                    'applicant_id' => $user->id,
                ]);
            }
        }

        return $this->successResponse($position, "You have successfully applied for the job.");
    }
}
