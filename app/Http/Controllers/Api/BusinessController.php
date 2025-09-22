<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessProfile\GalleryRequest;
use App\Http\Requests\BusinessProfile\JobPostRequest;
use App\Http\Requests\BusinessProfile\ProfileRequest;
use App\Services\BusinessProfile\DeleteJobPostService;
use App\Services\BusinessProfile\GalleryService;
use App\Services\BusinessProfile\GetGalleryService;
use App\Services\BusinessProfile\GetJobPostService;
use App\Services\BusinessProfile\GetProfileService;
use App\Services\BusinessProfile\JobPostService;
use App\Services\BusinessProfile\ProfileService;
use App\Services\BusinessProfile\UpdateJobPostService;
use App\Services\Job\ViewJobPostService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    protected $profileService;
    protected $getProfileService;
    protected $getGalleryService;
    protected $galleryService;
    protected $jobPostService;
    protected $getJobPostService;
    protected $udpateJobPostService;
    protected $deleteJobPostService;
    public function __construct(
        ProfileService $profileService,
        GetProfileService $getProfileService,
        GalleryService $galleryService,
        GetGalleryService $getGalleryService,
        JobPostService $jobPostService,
        GetJobPostService $getJobPostService,
        UpdateJobPostService $updateJobPostService,
        DeleteJobPostService $deleteJobPostService,

    ){
        $this->profileService = $profileService;
        $this->getProfileService = $getProfileService;
        $this->getGalleryService = $getGalleryService;
        $this->galleryService = $galleryService;
        $this->getJobPostService = $getJobPostService;
        $this->jobPostService = $jobPostService;
        $this->udpateJobPostService = $updateJobPostService;
        $this->deleteJobPostService = $deleteJobPostService;
    }
    public function getProfile()
    {
        return $this->execute(function(){
            return $this->getProfileService->getProfile();
        });
    }
    public function profile(ProfileRequest $profileRequest)
    {
        return $this->execute(function () use ($profileRequest){
            $data = $profileRequest->validated();
            return $this->profileService->profile($data);
        });
    }
    public function getGallery()
    {
        return $this->execute(function(){
            return $this->getGalleryService->getGallery();
        });
    }
    public function Gallery(GalleryRequest $galleryRequest)
    {
        return $this->execute(function() use($galleryRequest){
            $data = $galleryRequest->validated();
            return $this->galleryService->Gallery($data);
        });
    }
    public function getJobPost(Request $request)
    {
        return $this->execute(function() use ($request){
            return $this->getJobPostService->getJobPost($request);
        });
    }
    public function jobPost(JobPostRequest $jobPostRequest)
    {
        return $this->execute(function() use($jobPostRequest){
            $data = $jobPostRequest->validated();
            return $this->jobPostService->jobPost($data);
        });
    }
    public function updateJobPost(JobPostRequest $jobPostRequest, $job_post_id)
    {
        return $this->execute(function() use($jobPostRequest,$job_post_id){
            $data = $jobPostRequest->validated();
            return $this->udpateJobPostService->updateJobPost($data, $job_post_id);
        });
    }
    public function deleteJobPost($job_post_id)
    {
        return $this->execute(function() use($job_post_id){
            return $this->deleteJobPostService->deleteJobPost($job_post_id);
        });
    }
}
