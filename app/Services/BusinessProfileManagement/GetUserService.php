<?php

namespace App\Services\BusinessProfileManagement;

use App\Models\BusinessProfile;
use App\Traits\ResponseHelper;

class GetUserService
{
    use ResponseHelper;

    public function getUser($request)
    {
        $keyword   = $request->keyword ?? null;
        $perPage   = $request->per_page ?? 20;
        $category  = $request->category ?? null; // art_id
        $isBanned  = $request->is_banned ?? 0; 

        $query = BusinessProfile::with('user', 'art')
            ->whereHas('user', function ($q) use ($isBanned) {
                $q->where('is_banned', $isBanned);
            });

        // Filter by category (art_id)
        if ($category) {
            $query->where('art_id', $category);
        }

        // Keyword search
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('business_name', 'like', "%$keyword%")
                  ->orWhere('location', 'like', "%$keyword%")
                  ->orWhere('description', 'like', "%$keyword%")
                  ->orWhereHas('user', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%$keyword%")
                         ->orWhere('email', 'like', "%$keyword%");
                  });
            });
        }

        // Paginate results
        $profiles = $query->orderBy('id', 'desc')->paginate($perPage);

        if ($profiles->isEmpty()) {
            return $this->successResponse([], 'No business profiles found.');
        }

        // Format each profile
        $profiles->transform(function ($profile) {
            return [
                'id'            => $profile->id,
                'business_name' => $profile->business_name,
                'location'      => $profile->location,
                'description'   => $profile->description,
                'user_name'     => $profile->user->name ?? null,
                'user_email'    => $profile->user->email ?? null,
                'art_name'      => $profile->art->name ?? null,
                'avatar'        => $profile->avatar ? asset($profile->avatar) : null,
                'cover_picture' => $profile->cover_picture ? asset($profile->cover_picture) : null,
            ];
        });

        return $this->successResponse($profiles, 'Business profiles retrieved successfully.');
    }
}
