<?php

namespace App\Services\Home;

use App\Models\Post;
use App\Models\Report;
use App\Traits\ResponseHelper;
use App\Services\Notification\NotificationService;

class ReportService
{
    use ResponseHelper;

    public function report($data)
    {
        $userId = auth()->id();
        $postId = $data['post_id'];

        // Fetch the post to check ownership
        $post = Post::find($postId);
        if (!$post) {
            return $this->errorResponse('Post not found.');
        }

        // Prevent user from reporting their own post
        if ($post->user_id == $userId) {
            return $this->errorResponse('You cannot report your own post.');
        }

        // Check if this user already reported this post
        $existingReport = Report::where('user_id', $userId)
                                ->where('post_id', $postId)
                                ->first();

        if ($existingReport) {
            return $this->errorResponse('You have already reported this post.');
        }

        // Create new report
        $data['user_id'] = $userId;
        $report = Report::create($data);

        // Send notification to post owner
        if ($post->user_id) {
            $notificationService = new NotificationService();
            try {
                $notificationService->send($post->user_id, [
                    'title'   => 'Your post was reported',
                    'message' => 'One of your posts has been reported by a user.',
                    'type'    => 'REPORT_ACTION',
                    'report_id' => $report->id
                ]);
            } catch (\Exception $e) {
                \Log::error("Notification failed: " . $e->getMessage());
            }
        }

        return $this->successResponse($report, 'Report submitted successfully.');
    }
}
