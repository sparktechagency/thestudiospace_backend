<?php

namespace App\Services\Network;

use App\Models\Conection;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class RequestService
{
    use ResponseHelper;

    /**
     * Get pending connection requests for authenticated user
     */
    public function requests($request)
    {
        $authUser = Auth::user();

        // Fetch connections where the auth user is the recipient and status is pending
        $pendingRequests = Conection::with('user') // load the sender
            ->where('connection_id', $authUser->id)
            ->where('status', 'pending')
            ->get();

        // Format response
        $pendingRequests->each(function($request) {
            $request->sender_name = $request->user->name ?? null;
            $request->sender_designation = $request->user->userInfo
                ? ($request->user->userInfo->job_title . ' at ' . ($request->user->userInfo->company_name ?? ''))
                : null;
        });

        $count = $pendingRequests->count();

        return $this->successResponse([
            'count' => $count,
            'requests' => $pendingRequests
        ], "Pending requests retrieved successfully.");
    }
}
