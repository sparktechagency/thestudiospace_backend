<?php

namespace App\Services\Dashboard;

use App\Models\JobPost;
use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboard
{
    use ResponseHelper;

    public function dashboard($request)
    {
        $now = now();
        $lastWeekStart = $now->copy()->subDays(7);
        $twoWeeksAgoStart = $now->copy()->subDays(14);

        // --- Total Users ---
        $totalUsers = User::where('role', 'USER')->count();
        $lastWeekUsers = User::where('role', 'USER')
            ->whereBetween('created_at', [$twoWeeksAgoStart, $lastWeekStart])
            ->count();
        $usersGrowth = $lastWeekUsers > 0 ? round((($totalUsers - $lastWeekUsers) / $lastWeekUsers) * 100, 1) : 0;

        // --- Active Job Listings ---
        $activeJobs = JobPost::where('status', 'approved')->count();
        $lastWeekJobs = JobPost::where('status', 'approved')
            ->whereBetween('created_at', [$twoWeeksAgoStart, $lastWeekStart])
            ->count();
        $jobsGrowth = $lastWeekJobs > 0 ? round((($activeJobs - $lastWeekJobs) / $lastWeekJobs) * 100, 1) : 0;

        // --- Content Posted ---
        $totalPosts = Post::count();
        $lastWeekPosts = Post::whereBetween('created_at', [$twoWeeksAgoStart, $lastWeekStart])->count();
        $postsGrowth = $lastWeekPosts > 0 ? round((($totalPosts - $lastWeekPosts) / $lastWeekPosts) * 100, 1) : 0;

        // --- Business Profiles ---
        $businessProfiles = User::where('user_type', 'BUSINESS')->count();
        $lastWeekBusiness = User::where('user_type', 'BUSINESS')
            ->whereBetween('created_at', [$twoWeeksAgoStart, $lastWeekStart])
            ->count();
        $businessGrowth = $lastWeekBusiness > 0 ? round((($businessProfiles - $lastWeekBusiness) / $lastWeekBusiness) * 100, 1) : 0;

        // --- Reported Posts ---
        $reportedPosts = Report::count();
        $lastWeekReports = Report::whereBetween('created_at', [$twoWeeksAgoStart, $lastWeekStart])->count();
        $reportsGrowth = $lastWeekReports > 0 ? round((($reportedPosts - $lastWeekReports) / $lastWeekReports) * 100, 1) : 0;

        // --- Recent Post Activities (latest 5 posts) ---
        $recentActivities = Post::latest()
            ->take(5)
            ->with('user:id,name,email,role,user_type')
            ->get(['id','user_id','content','created_at']);

        // --- User Growth Last 7 Days ---
        $userGrowth = User::select(
            DB::raw('DAYNAME(created_at) as day'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', $now->subDays(7))
        ->groupBy(DB::raw('DAYNAME(created_at)'))
        ->orderByRaw("FIELD(DAYNAME(created_at), 'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday')")
        ->get()
        ->mapWithKeys(function($item) {
            return [$item->day => $item->count];
        });

        // Fill missing days with 0
        $daysOfWeek = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
        $userGrowthFull = [];
        foreach ($daysOfWeek as $day) {
            $userGrowthFull[$day] = $userGrowth->get($day, 0);
        }

        $data = [
            'total_users'           => $totalUsers,
            'total_users_growth'    => $usersGrowth,
            'active_jobs'           => $activeJobs,
            'active_jobs_growth'    => $jobsGrowth,
            'total_posts'           => $totalPosts,
            'total_posts_growth'    => $postsGrowth,
            'business_profiles'     => $businessProfiles,
            'business_profiles_growth'=> $businessGrowth,
            'reported_posts'        => $reportedPosts,
            'reported_posts_growth' => $reportsGrowth,
            'recent_activities'     => $recentActivities,
            'user_growth_7_days'    => $userGrowthFull,
        ];

        return $this->successResponse($data, "Dashboard data retrieved successfully.");
    }
}
