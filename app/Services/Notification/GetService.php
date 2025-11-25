<?php

namespace App\Services\Notification;

use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetService
{
    use ResponseHelper;

    public function index($request)
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 10);
        $notifications = $user->notifications()->paginate($perPage);
        return response()->json([
            'success' => true,
            'count' => $notifications->total(),
            'data' => $notifications,
        ]);
    }
}
