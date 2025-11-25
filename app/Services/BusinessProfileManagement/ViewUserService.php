<?php

namespace App\Services\BusinessProfileManagement;

use App\Models\BusinessProfile;
use App\Models\JobPost;
use App\Models\Follower;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\DB;

class ViewUserService
{
    use ResponseHelper;

    /**
     * View a single business profile by user ID
     */
    public function viewUser($userId)
    {
        // Fetch business profile with related user and art
        $profile = BusinessProfile::with(['user', 'art'])
            ->where('user_id', $userId)
            ->first();

        if (!$profile) {
            return $this->errorResponse('Business profile not found.');
        }

        $user = $profile->user;

        // Followers count
        // $followersCount = Follower::where('followed_id', $userId)->count();

        // Total job posts
        $totalJobPosts = JobPost::where('user_id', $userId)->count();

        // Active job posts (assuming 'status' column exists)
        $activeJobPosts = JobPost::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();

        // Last active timestamp
        $lastActive = $user->last_active_at ?? $user->updated_at;

        // Format response
        $profileData = [
            'user_name'        => $user->name ?? null,
            'user_email'       => $user->email ?? null,
            'business_name'    => $profile->business_name,
            'location'         => $profile->location,
            'description'      => $profile->description,
            'website'          => $profile->website,
            'social_links'     => $profile->social_links ? json_decode($profile->social_links, true) : [],
            'privacy_settings' => $profile->privacy_settings,
            'avatar'           => $profile->avatar ? $profile->avatar : null,
            'cover_picture'    => $profile->cover_picture ? asset($profile->cover_picture) : null,
            'art_name'         => $profile->art->name ?? null,
            // 'followers_count'  => $followersCount,
            'total_job_posts'  => $totalJobPosts,
            'active_job_posts' => $activeJobPosts,
            'last_active'      => $lastActive,
        ];

        return $this->successResponse($profileData, 'Business profile retrieved successfully.');
    }
}
