<?php

namespace App\Services\Home;

use App\Models\Report;
use App\Traits\ResponseHelper;

class ReportService
{
    use ResponseHelper;
    public function report($data)
    {
        $data['user_id'] = auth()->id();
        $report = Report::create($data);
        return $this->successResponse($report, 'Report submitted successfully.');
    }
}
