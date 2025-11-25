<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Network\ConnectionService;
use App\Services\Network\DiscoverService;
use App\Services\Network\RequestService;
use App\Services\Network\VisitorsService;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    protected $connectionService;
    protected $discoverService;
    protected $requestService;
    protected $visitorsService;

    public function __construct(
        ConnectionService $connectionService,
        DiscoverService $discoverService,
        RequestService $requestService,
        VisitorsService $visitorsService
    ){
        $this->connectionService = $connectionService;
        $this->discoverService = $discoverService;
        $this->requestService = $requestService;
        $this->visitorsService = $visitorsService;
    }

    // Get connections
    public function connections(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->connectionService->connections($request);
        });
    }

    // Discover users/groups
    public function discovers(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->discoverService->discovers($request);
        });
    }

    // Connection requests
    public function requests(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->requestService->requests($request);
        });
    }

    // Visitors
    public function visitors(Request $request)
    {
        return $this->execute(function() use ($request) {
            return $this->visitorsService->visitors($request);
        });
    }
}
