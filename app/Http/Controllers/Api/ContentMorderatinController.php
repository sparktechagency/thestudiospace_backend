<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ContentManagement\Approve;
use App\Services\ContentManagement\GetReport;
use App\Services\ContentManagement\RemoveContent;
use App\Services\ContentManagement\ViewReport;
use Illuminate\Http\Request;

class ContentMorderatinController extends Controller
{
    protected $getReport;
    protected $viewReport;
    protected $approve;
    protected $removeContent;
    public function __construct(
        GetReport $getReport,
        ViewReport $viewReport,
        Approve $approve,
        RemoveContent $removeContent
    )
    {
        $this->getReport = $getReport;
        $this->viewReport = $viewReport;
        $this->approve = $approve;
        $this->removeContent = $removeContent;
    }
    public function getReport(Request $request)
    {
        return $this->execute(function () use ($request) {
            return $this->getReport->getReport($request);
        });
    }
    public function viewReport(Request $request,$id)
    {
        return $this->execute(function () use ($request,$id) {
            return $this->viewReport->viewReport($request,$id);
        });
    }
     public function approve($id)
    {
        return $this->execute(function () use ($id) {
            return $this->approve->approve($id);
        });
    }
     public function reject($id)
    {
        return $this->execute(function () use ($id) {
            return $this->removeContent->reject($id);
        });
    }
}
