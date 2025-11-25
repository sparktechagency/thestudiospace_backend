<?php

namespace App\Services\Network;

use App\Models\ProfileView; // Model for profile_views table
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class VisitorsService
{
    use ResponseHelper;

    /**
     * Get recent profile visitors
     */
    public function visitors($request)
    {
        $authUser = Auth::user();

        // Optional: limit number of visitors returned
        $limit = $request->get('per_page', 20);

        // Get recent visitors to authenticated user's profile
        $visitors = ProfileView::with('viewer') // load user who viewed
            ->where('visited_id', $authUser->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        // Format response
        $visitors->each(function($visitor) {
            $visitor->name = $visitor->viewer->name ?? null;
            $visitor->avatar = $visitor->viewer->avatar ?? null;
            $visitor->designation = $visitor->viewer->userInfo
                ? ($visitor->viewer->userInfo->job_title . ' at ' . ($visitor->viewer->userInfo->company_name ?? ''))
                : null;
        });

        $count = $visitors->count();

        return $this->successResponse([
            'count' => $count,
            'visitors' => $visitors
        ], "Profile visitors retrieved successfully.");
    }
}
