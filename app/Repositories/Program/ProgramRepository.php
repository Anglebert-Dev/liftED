<?php

namespace App\Repositories\Program;

use App\Models\Program\Program;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProgramRepository extends BaseRepository
{
    public function __construct(Program $model)
    {
        parent::__construct($model);
    }

    public function getAllForNgo(int $ngoId): Collection
    {
        return $this->model->forNgo($ngoId)->with('ngo')->latest()->get();
    }

    public function getPaginatedForNgo(int $ngoId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->forNgo($ngoId)
            ->with('ngo')
            ->withCount(['learningMaterials', 'enrollments'])
            ->latest()
            ->paginate($perPage);
    }

    public function getPaginatedForLearner(int $learnerId, int $ngoId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->forNgo($ngoId)
            ->whereHas('enrollments', fn ($q) => $q->where('learner_id', $learnerId))
            ->with('ngo')
            ->withCount(['learningMaterials', 'enrollments'])
            ->latest()
            ->paginate($perPage);
    }

    public function getWithMaterialCount(int $ngoId): Collection
    {
        return $this->model
            ->forNgo($ngoId)
            ->withCount('learningMaterials')
            ->withCount('enrollments')
            ->latest()
            ->get();
    }

    public function findByUuidWithRelations(string $uuid): ?Program
    {
        return $this->model
            ->with(['ngo', 'learningMaterials', 'enrollments.learner', 'enrollments.mentor'])
            ->where('uuid', $uuid)
            ->first();
    }
}
