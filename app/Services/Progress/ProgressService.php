<?php

namespace App\Services\Progress;

use App\Repositories\Progress\ProgressRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class ProgressService extends BaseService
{
    public function __construct(private ProgressRepository $repo) {}

    public function logView(int $learnerId, int $materialId, int $programId): array
    {
        try {
            $progress           = $this->repo->findOrCreateForLearnerMaterial($learnerId, $materialId, $programId);
            $progress->viewed_at = now();

            if (is_null($progress->completion_status)) {
                $progress->completion_status = 'in_progress';
            }

            $progress->save();

            return $this->success($progress, 'Progress logged.');
        } catch (\Throwable $e) {
            return $this->failure('Could not log progress.');
        }
    }

    public function logDownload(int $learnerId, int $materialId, int $programId): array
    {
        try {
            $progress                  = $this->repo->findOrCreateForLearnerMaterial($learnerId, $materialId, $programId);
            $progress->downloaded_at   = now();
            $progress->completion_status = 'completed';
            $progress->save();

            return $this->success($progress, 'Download logged.');
        } catch (\Throwable $e) {
            return $this->failure('Could not log download.');
        }
    }

    public function saveFeedback(Request $request): array
    {
        try {
            $feedback = $this->repo->saveFeedback([
                'mentor_id'  => auth()->id(),
                'learner_id' => $request->input('learner_id'),
                'program_id' => $request->input('program_id'),
                'content'    => $request->input('content'),
            ]);

            return $this->success($feedback, 'Feedback saved.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save feedback.');
        }
    }
}
