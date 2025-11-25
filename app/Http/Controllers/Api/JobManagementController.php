<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\JobManagement\ApprovedService;
use App\Services\JobManagement\RejectService;
use App\Services\JobManagement\DeleteService;
use App\Services\JobManagement\GetJobService;
use App\Services\JobManagement\ViewJobService;

class JobManagementController extends Controller
{
    protected $approvedService;
    protected $rejectService;
    protected $deleteService;
    protected $getJobService;
    protected $viewJobService;

    // Dependency Injection of services
    public function __construct(
        ApprovedService $approvedService,
        RejectService $rejectService,
        DeleteService $deleteService,
        GetJobService $getJobService,
        ViewJobService $viewJobService
    ) {
        $this->approvedService = $approvedService;
        $this->rejectService   = $rejectService;
        $this->deleteService   = $deleteService;
        $this->getJobService   = $getJobService;
        $this->viewJobService  = $viewJobService;
    }

    // Approve a job
    public function approve($id)
    {
        return $this->execute(function()use($id){
            return $this->approvedService->approve($id);
        });
    }

    // Reject a job
   public function reject($id)
    {
        return $this->execute(function()use($id){
            return $this->rejectService->reject($id);
        });
    }

    // Delete a job
    public function delete($id)
    {
        return $this->execute(function()use($id){
            return $this->deleteService->delete($id);
        });
    }

    // Get all jobs (with filters if needed)
    public function getJob(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->getJobService->getJob($request);
        });
    }
    public function viewJob($id)
    {
         return $this->execute(function()use ($id){
             return $this->viewJobService->viewJob($id);
        });
    }
}
