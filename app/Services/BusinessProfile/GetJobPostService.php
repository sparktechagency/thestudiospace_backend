<?php

namespace App\Services\BusinessProfile;

use App\Models\JobPost;
use App\Models\SavedJob;
use App\Models\UserInfo;
use App\Traits\ResponseHelper;
use Illuminate\Support\Facades\Auth;

class GetJobPostService
{
   use ResponseHelper;

    public function getJobPost($request)
    {
        $job_posts = $this->applyFiltersAndSorting($request);
         foreach ($job_posts as $job_post) {
            if (isset($job_post->required_skills) && is_string($job_post->required_skills)) {
                $job_post->required_skills = json_decode($job_post->required_skills, true);
            }
             $job_post->is_saved = $this->getSavedJobStatus($job_post->id);
        }
        return $this->successResponse($job_posts, "Job posts fetched successfully.");
    }
    private function applyFiltersAndSorting($request)
    {
        $query = JobPost::with(['user:id,name,email,avatar', 'art:id,name', 'userInfo']);
        if ($request->has('search') && !empty($request->search)) {
            $query->where('job_title', 'LIKE', '%' . $request->search . '%')
                ->orWhere('job_description', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->has('job_type') && !empty($request->job_type)) {
            $query->whereIn('job_type', $request->job_type);
        }
        if ($request->has('art_id') && !empty($request->art_id)) {
            $query->whereIn('art_id', $request->art_id);
        }
        if ($request->has('location') && !empty($request->location)) {
            $query->whereIn('location', $request->location);
        }
        $sortBy = $request->get('sort_by', 'desc');
        $sortColumn = $request->get('sort_column', 'id');
        $query->orderBy($sortColumn, $sortBy);
        return $query->paginate(20);
    }
     private function getSavedJobStatus($job_post_id)
    {
        $user_id = Auth::id();
        $savedJob = SavedJob::where('user_id', $user_id)
                            ->where('job_post_id', $job_post_id)
                            ->first();
        return $savedJob ? $savedJob->status : false;
    }
}
