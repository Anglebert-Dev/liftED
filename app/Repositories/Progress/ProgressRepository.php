<?php

namespace App\Repositories\Progress;

use App\Models\Progress\Progress;
use App\Models\Progress\Feedback;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ProgressRepository extends BaseRepository
{
    public function __construct(Progress $model)
    {
        parent::__construct($model);
    }

    public function findOrCreateForLearnerMaterial(int $learnerId, int $materialId, int $programId): Progress
    {
        return $this->model->firstOrCreate(
            ['learner_id' => $learnerId, 'material_id' => $materialId, 'program_id' => $programId]
        );
    }

    public function getAllForLearnerInProgram(int $learnerId, int $programId): Collection
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->with(['material', 'feedback'])
            ->latest()
            ->get();
    }

    public function getAllForMentorLearners(int $mentorId): Collection
    {
        return $this->model
            ->whereHas('material.program.enrollments', function ($q) use ($mentorId) {
                $q->where('mentor_id', $mentorId);
            })
            ->with(['learner', 'material', 'program'])
            ->latest()
            ->get();
    }

    public function getWithStats(int $learnerId, int $programId): Collection
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->with(['material', 'feedback'])
            ->get();
    }

    // Feedback

    public function saveFeedback(array $data): Feedback
    {
        return Feedback::updateOrCreate(
            [
                'mentor_id'  => $data['mentor_id'],
                'learner_id' => $data['learner_id'],
                'program_id' => $data['program_id'],
            ],
            ['content' => $data['content']]
        );
    }

    public function getFeedbackForLearnerInProgram(int $learnerId, int $programId): ?Feedback
    {
        return Feedback::where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->first();
    }
}
