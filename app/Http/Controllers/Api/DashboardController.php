<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\adminDashboard;
use App\Services\Dashboard\Analytics;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $adminDashboard;
    protected $analytics;

    public function __construct(
        adminDashboard $adminDashboard,
        Analytics $analytics,
    )
    {
        $this->adminDashboard = $adminDashboard;
        $this->analytics = $analytics;
    }
    public function dashboard(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->adminDashboard->dashboard($request);
        });
    }
    public function analytics(Request $request)
    {
        return $this->execute(function()use($request){
            return $this->analytics->analytics($request);
        });
    }

}
