<?php

namespace App\Services\ContentManagement;

use App\Models\Report;
use App\Traits\ResponseHelper;
use Carbon\Carbon;

class GetReport
{
    use ResponseHelper;

    public function getReport($data)
    {
        $query = Report::query()->with(['user', 'post']); // Include user and post data

        // Search by name or description
        if (!empty($data->search)) {
            $search = $data->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by content type
        if (!empty($data->content_type)) {
            $query->where('type', $data->content_type);
        }

        // Filter by status
        if (!empty($data->status)) {
            $query->where('status', $data->status);
        }

        // Filter by report reason
        if (!empty($data->reason)) {
            $query->where('name', $data->reason);
        }

        // Filter by date range
        if (!empty($data->from_date)) {
            $query->whereDate('created_at', '>=', $data->from_date);
        }
        if (!empty($data->to_date)) {
            $query->whereDate('created_at', '<=', $data->to_date);
        }

        // Pagination
        $perPage = $data->per_page ?? 10;
        $reports = $query->latest()->paginate($perPage);

        // Dashboard counts
        $totalReports = Report::count();
        $pendingReports = Report::where('status', 'Unreviewed')->count();
        $resolvedToday = Report::where('status', 'Approved')
                               ->whereDate('updated_at', Carbon::today())
                               ->count();
        $contentRemoved = Report::where('status', 'Removed')->count();

        $summary = [
            'total_reports' => $totalReports,
            'pending_reports' => $pendingReports,
            'resolved_today' => $resolvedToday,
            'content_removed' => $contentRemoved,
        ];

        return $this->successResponse([
            'summary' => $summary,
            'reports' => $reports
        ], "Reports fetched successfully");
    }
}
