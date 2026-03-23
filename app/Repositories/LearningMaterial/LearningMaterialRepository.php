<?php

namespace App\Repositories\LearningMaterial;

use App\Models\LearningMaterial\LearningMaterial;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class LearningMaterialRepository extends BaseRepository
{
    public function __construct(LearningMaterial $model)
    {
        parent::__construct($model);
    }

    public function getByProgram(int $programId): Collection
    {
        return $this->model->where('program_id', $programId)->latest()->get();
    }

    public function getByProgramForLearner(int $programId, int $learnerId): Collection
    {
        // Only return materials from programs the learner is enrolled in
        return $this->model
            ->where('program_id', $programId)
            ->whereHas('program.enrollments', function ($q) use ($learnerId) {
                $q->where('learner_id', $learnerId);
            })
            ->latest()
            ->get();
    }
}
