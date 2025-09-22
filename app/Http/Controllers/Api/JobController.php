<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Job\JobRequest;
use App\Services\BusinessProfile\GetJobPostService;
use App\Services\Job\ApplyNowService;
use App\Services\Job\ViewJobPostService;
use Illuminate\Http\Request;

class JobController extends Controller
{
    protected $getJobPostService;
    protected $viewJobPostService;
    protected $applyNowService;
    public function __construct(
        GetJobPostService $getJobPostService,
        ViewJobPostService $viewJobPostService,
        ApplyNowService $applyNowService,
    ){
        $this->getJobPostService = $getJobPostService;
        $this->viewJobPostService = $viewJobPostService;
        $this->applyNowService = $applyNowService;
    }
    public function getJobPost(Request $request)
    {
        return $this->execute(function() use ($request){
            return $this->getJobPostService->getJobPost($request);
        });
    }
    public function viewJobPost($job_post_id)
    {
        return $this->execute(function() use ($job_post_id){
            return $this->viewJobPostService->viewJobPost($job_post_id);
        });
    }
    public function applyNow(JobRequest $jobRequest, $job_post_id)
    {
        return $this->execute(function() use ($jobRequest,$job_post_id){
            $data = $jobRequest->validated();
            return $this->applyNowService->applyNow($data,$job_post_id);
        });
    }
}
