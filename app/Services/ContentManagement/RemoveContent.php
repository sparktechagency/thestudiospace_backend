<?php

namespace App\Services\ContentManagement;

use App\Models\Report;
use App\Traits\ResponseHelper;
use App\Services\Notification\NotificationService;
use Illuminate\Support\Facades\DB;

class RemoveContent
{
    use ResponseHelper;

    public function reject($id)
    {
        $report = Report::with(['user', 'post.user'])->find($id);

        if (!$report) {
            return $this->errorResponse("Report not found.", 404);
        }

        if ($report->status === 'Removed') {
            return $this->errorResponse("Report already removed.", 400);
        }

        DB::transaction(function () use ($report) {
            $report->update(['status' => 'Removed']);

            $post = $report->post;

            if ($post) {
                $postOwner = $post->user;
                $post->delete();
            }

            $notificationService = new NotificationService();

            // Notify post owner
            if (!empty($postOwner)) {
                try {
                    $notificationService->send($postOwner->id, [
                        'title'   => 'Content Removed',
                        'message' => 'Your post has been removed due to violation.',
                        'type'    => 'REPORT_ACTION'
                    ]);
                } catch (\Exception $e) {
                    // Log failure but do not rollback transaction
                    \Log::error("Notification failed: ".$e->getMessage());
                }
            }

            // Notify reporter
            if ($report->user) {
                try {
                    $notificationService->send($report->user->id, [
                        'title'   => 'Report Reviewed',
                        'message' => 'Your report has been reviewed and content removed.',
                        'type'    => 'REPORT_ACTION'
                    ]);
                } catch (\Exception $e) {
                    \Log::error("Notification failed: ".$e->getMessage());
                }
            }
        });

        return $this->successResponse($report, "Report rejected and content removed successfully.");
    }
}
