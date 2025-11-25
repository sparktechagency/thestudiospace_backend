<?php

namespace App\Services\JobManagement;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class GetJobService
{
    use ResponseHelper;

    public function getJob($request)
    {
        $keyword = $request->keyword ?? null;
        $categoryId = $request->category ?? null;
        $status = $request->status ?? null;
        $perPage = $request->per_page ?? 20;

        // Include applicant count with 'positions' relationship
        $query = JobPost::with('art', 'user')->withCount('positions');

        // Filter by category/art
        if ($categoryId) {
            $query->where('art_id', $categoryId);
        }

        // Filter by status
        if ($status) {
            $query->where('status', $status);
        }

        // Keyword search
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('job_title', 'like', "%$keyword%")
                  ->orWhere('job_description', 'like', "%$keyword%")
                  ->orWhereHas('user', function ($q2) use ($keyword) {
                      $q2->where('name', 'like', "%$keyword%")
                         ->orWhere('email', 'like', "%$keyword%");
                  });
            });
        }

        $jobs = $query->orderByDesc('id')->paginate($perPage);

        // Transform collection for frontend
        $jobs->getCollection()->transform(function ($job) {
            return [
                'id' => $job->id,
                'job_title' => $job->job_title,
                'job_description' => $job->job_description,
                'art_name' => $job->art->name ?? null,
                'user_name' => $job->user->name ?? null,
                'user_email' => $job->user->email ?? null,
                'job_type' => $job->job_type,
                'location' => $job->location,
                'application_deadline' => $job->application_deadline,
                'required_skills' => $job->required_skills ? json_decode($job->required_skills) : [],
                'start_budget' => $job->start_budget,
                'end_budget' => $job->end_budget,
                'status' => $job->status,
                'applicant_count' => $job->positions_count, // <-- applicant count
                'created_at' => $job->created_at->format('Y-m-d H:i:s'),
            ];
        });

        if ($jobs->total() === 0) {
            return $this->successResponse([], 'No jobs found.');
        }

        return $this->successResponse($jobs, 'Jobs retrieved successfully.');
    }
}
