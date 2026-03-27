<?php
    namespace App\Services\ContentManagement;

    use App\Models\Report;
    use App\Traits\ResponseHelper;
    use App\Services\Notification\NotificationService;

    class Approve
    {
        use ResponseHelper;

        public function approve($id)
        {
            $report = Report::with(['user', 'post.user'])->find($id);

            if (!$report) {
                return $this->errorResponse("Report not found.", 404);
            }

            if ($report->status === 'approved') {
                return $this->errorResponse("Report already approved.", 400);
            }

            // Update report status
            $report->update([
                'status' => 'approved'
            ]);

            // ✅ Safe check
            if ($report->post && $report->post->user) {

                $notificationData = [
                    'title'   => 'Report Approved',
                    'message' => 'A report on your post has been approved by admin.',
                    'type'    => 'REPORT_ACTION'
                ];
                (new NotificationService())->send(
                    $report->post->user->id,
                    $notificationData
                );
            }
            return $this->successResponse($report, "Report approved successfully.");
        }
}

