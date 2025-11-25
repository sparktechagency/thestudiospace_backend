<?php

namespace App\Services\JobManagement;

use App\Models\JobPost;
use App\Traits\ResponseHelper;

class ViewJobService
{
    use ResponseHelper;

    public function viewJob($id)
    {
        // Retrieve job with related user, art, and count of applicants
        $job = JobPost::with('art', 'user')
            ->withCount('positions')
            ->find($id);

        if (!$job) {
            return $this->successResponse([], 'Job not found.');
        }

        // Format data for frontend
        $data = [
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
            'applicant_count' => $job->positions_count,
            'created_at' => $job->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $job->updated_at->format('Y-m-d H:i:s'),
        ];

        return $this->successResponse($data, 'Job retrieved successfully.');
    }
}
