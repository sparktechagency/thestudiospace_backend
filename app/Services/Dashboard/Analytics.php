<?php

namespace App\Services\Dashboard;

use App\Traits\ResponseHelper;
use App\Models\User;
use App\Models\BusinessProfile;
use App\Models\JobPost;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Analytics
{
    use ResponseHelper;

    public function analytics($request)
    {
        $dateRange = $request->date_range ?? 'weekly'; // default weekly
        $now = now();

        // -------------------------------
        // Determine start date & timeline labels
        // -------------------------------
        switch ($dateRange) {
            case 'weekly':
                $startDate = $now->copy()->subDays(6); // last 7 days including today
                $labels = [];
                for ($i = 0; $i < 7; $i++) {
                    $labels[] = $startDate->copy()->addDays($i)->format('l'); // Sunday, Monday...
                }
                $groupByRaw = 'DAYNAME(created_at)';
                $labelKey = fn($date) => Carbon::parse($date)->format('l');
                break;

            case 'monthly':
                $startDate = $now->copy()->startOfMonth();
                $daysInMonth = $now->daysInMonth;
                $labels = range(1, $daysInMonth); // 1,2,3..30/31
                $groupByRaw = 'DAY(created_at)';
                $labelKey = fn($date) => Carbon::parse($date)->day;
                break;

            case 'yearly':
                $startDate = $now->copy()->startOfYear();
                $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                $groupByRaw = "MONTH(created_at)";
                $labelKey = fn($monthNumber) => Carbon::create()->month($monthNumber)->format('M');
                break;

            default:
                $startDate = $now->copy()->subDays(6);
                $labels = [];
                for ($i = 0; $i < 7; $i++) {
                    $labels[] = $startDate->copy()->addDays($i)->format('l');
                }
                $groupByRaw = 'DAYNAME(created_at)';
                $labelKey = fn($date) => Carbon::parse($date)->format('l');
        }

        // -------------------------------
        // 1️⃣ User Growth
        // -------------------------------
        $usersQuery = User::select(
                DB::raw("$groupByRaw as label"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw($groupByRaw))
            ->get()
            ->mapWithKeys(fn($item) => [$labelKey($item->label) => $item->count]);

        // Fill missing labels with 0
        $userGrowthFull = [];
        foreach ($labels as $label) {
            $userGrowthFull[$label] = $usersQuery->get($label, 0);
        }

        // -------------------------------
        // 2️⃣ Jobs By Art
        // -------------------------------
        $jobsByArt = JobPost::select('arts.name as art', DB::raw('COUNT(*) as total'))
            ->join('arts', 'job_posts.art_id', '=', 'arts.id')
            ->where('job_posts.created_at', '>=', $startDate)
            ->groupBy('arts.name')
            ->get();

        // -------------------------------
        // 3️⃣ Top Engaged Users (Posts)
        // -------------------------------
        $topUsers = User::withCount(['posts' => fn($q) => $q->where('created_at', '>=', $startDate)])
            ->orderBy('posts_count', 'desc')
            ->take(4)
            ->get()
            ->map(fn($user) => [
                'name' => $user->name,
                'posts' => $user->posts_count,
                'engagement_rate' => $user->posts_count ? '100%' : '0%',
            ]);

        // -------------------------------
        // 4️⃣ Top Business Profiles
        // -------------------------------
        $topBusinesses = BusinessProfile::withCount('followers')
            ->take(4)
            ->orderBy('followers_count', 'desc')
            ->get()
            ->map(fn($business) => [
                'name' => $business->name,
                'jobs_count' => $business->jobs()->where('created_at', '>=', $startDate)->count(),
                'followers' => $business->followers_count,
            ]);

        // -------------------------------
        // 5️⃣ Compile Response
        // -------------------------------
        $data = [
            'user_growth' => $userGrowthFull,
            'jobs_distribution' => $jobsByArt,
            'top_engaged_users' => $topUsers,
            'top_business_profiles' => $topBusinesses,
            'date_range' => $dateRange
        ];

        return $this->successResponse($data, 'Analytics data retrieved successfully.');
    }
}
