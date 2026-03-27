<?php

namespace App\Services\ContentManagement;

use App\Models\Report;
use App\Traits\ResponseHelper;

class ViewReport
{
    use ResponseHelper;
    public function viewReport($request, $id)
    {
        // Fetch the report with user and post counts
        $report = Report::with([
            'user',
            'post' => function ($query) {
                $query->withCount(['likes', 'comments', 'shares', 'savedPosts']); // Only counts
            }
        ])->find($id);

        if (!$report) {
            return $this->errorResponse("Report not found.", 404);
        }

        // Decode JSON fields in the post
        if ($report->post) {
            $report->post->photos = $report->post->photos ? json_decode($report->post->photos, true) : [];
            $report->post->video  = $report->post->video  ? json_decode($report->post->video, true)  : [];
            $report->post->document  = $report->post->document  ? json_decode($report->post->document, true)  : [];

            $report->post->report_count = Report::where('post_id', $report->post->id)->count();
        }

        return $this->successResponse($report, "Report fetched successfully");
    }
}
