<?php

namespace App\Http\Controllers\Web\Report;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function index()
    {
        A::require('read reports.report');

        $user = auth()->user();
        $ngoId = $user->hasRole('SuperAdmin') ? null : $user->ngo_id;

        $result = $this->service->overview($ngoId);
        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        $data = $result['data'] ?? [];

        return view('report.index', [
            'totals' => $data['totals'],
            'programSummaries' => $data['program_summaries'],
        ]);
    }
}

