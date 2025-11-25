<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use App\Services\Home\OnlineUserService;
use App\Services\Home\ReportService;
use App\Services\Home\SearchService;
use App\Services\Home\SuggestGroup;
use App\Services\Home\SuggestGroupService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $searchService;
    protected $reportService;
    protected $suggestGroupService;
    protected $onlineUserService;

    public function __construct(
        SearchService $searchService,
        ReportService $reportService,
        SuggestGroupService $suggestGroupService,
        OnlineUserService $onlineUserService,
    ){
        $this->searchService = $searchService;
        $this->reportService = $reportService;
        $this->suggestGroupService = $suggestGroupService;
        $this->onlineUserService = $onlineUserService;
    }
    public function search(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->searchService->search($request);
        });
    }
    public function report(ReportRequest $request)
    {
        return $this->execute(function() use ($request) {
             $data = $request->validated();
            return $this->reportService->report($data);
        });
    }
      public function suggestGroup(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->suggestGroupService->suggestGroup($request);
        });
    }
    public function onlineUser(Request $request)
    {
         return $this->execute(function() use ($request) {
            return $this->onlineUserService->onlineUser($request);
        });
    }
}
