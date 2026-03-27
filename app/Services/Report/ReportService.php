<?php

namespace App\Services\Report;

use App\Repositories\Report\ReportRepository;
use App\Services\BaseService;

class ReportService extends BaseService
{
    public function __construct(private ReportRepository $repo) {}

    public function overview(?int $ngoId = null): array
    {
        try {
            $programIds = $this->repo->programIds($ngoId);

            $totals = [
                'learners_onboarded' => $this->repo->onboardedLearnersCount($ngoId),
                'enrollments' => $this->repo->enrollmentsCount($ngoId),
                'learners_completed_any' => $this->repo->learnersCompletedAnyCount($programIds),
                'learners_completed_all' => $this->repo->learnersCompletedAllMaterialsCount($ngoId),
                'materials_total' => $this->repo->materialsTotalCount($programIds),
                'materials_completed' => $this->repo->materialsCompletedCount($programIds),
                'mentors_helped' => $this->repo->mentorsHelpedCount($ngoId),
                'mentors_total' => $this->repo->mentorCount($ngoId),
                'staff_total' => $this->repo->staffCount($ngoId),
                'learners_total' => $this->repo->learnerCount($ngoId),
            ];

            return $this->success([
                'totals' => $totals,
                'program_summaries' => $this->repo->programSummaries($ngoId),
            ], 'Report loaded.');
        } catch (\Throwable $e) {
            return $this->failure('Could not load report: ' . $e->getMessage());
        }
    }
}

