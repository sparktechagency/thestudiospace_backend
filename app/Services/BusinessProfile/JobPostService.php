<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Models\User;
use App\Models\Conection;
use App\Services\Notification\NotificationService;
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

        // Prepare payload
        $data['user_id'] = $user->id;

        if (isset($data['required_skills']) && is_array($data['required_skills'])) {
            $data['required_skills'] = json_encode($data['required_skills']);
        }

        // Create the job post
        $jobPost = JobPost::create($data);

        // ---------------------------------------
        //       SEND NOTIFICATION TO USERS
        // ---------------------------------------
        $receivers = $this->getReceivers($user->id);

        if (!empty($receivers)) {
            $notificationService = new NotificationService();

            foreach ($receivers as $receiver) {
                if ($receiver->fcm_token || $receiver) {

                    $notificationService->send($receiver, [
                        'title'   => "New Job Post",
                        'message' => $user->name . " posted a new job.",
                        'type'    => "job_post",
                        'job_id'  => $jobPost->id,
                    ]);
                }
            }
        }

        return $this->successResponse($jobPost, "Job post created successfully.");
    }



    // ---------------------------------------
    //       WHO WILL RECEIVE NOTIFICATION?
    // ---------------------------------------
    protected function getReceivers($userId)
    {
        // Example: Notify user's connections
        // You can change this logic (followers, all users, same industry etc.)

        $connectionIds = Conection::where('user_id', $userId)
                                    ->pluck('connection_id')
                                    ->where('status','accepted')
                                    ->toArray();

        return User::whereIn('id', $connectionIds)->get();
    }
}
