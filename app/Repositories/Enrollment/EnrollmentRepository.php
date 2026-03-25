<?php

namespace App\Repositories\Enrollment;

use App\Models\Enrollment\Enrollment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    public function getByProgram(int $programId): Collection
    {
        return $this->model
            ->where('program_id', $programId)
            ->with(['learner', 'mentor'])
            ->latest()
            ->get();
    }

    public function getByLearner(int $learnerId): Collection
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->with(['program', 'mentor'])
            ->latest()
            ->get();
    }

    public function getByMentor(int $mentorId): Collection
    {
        return $this->model
            ->where('mentor_id', $mentorId)
            ->with(['learner', 'program'])
            ->latest()
            ->get();
    }

    public function getByNgo(int $ngoId): Collection
    {
        return $this->model
            ->whereHas('program', fn ($q) => $q->where('ngo_id', $ngoId))
            ->with(['learner', 'mentor', 'program'])
            ->latest()
            ->get();
    }

    public function getAll(): Collection
    {
        return $this->model
            ->with(['learner', 'mentor', 'program'])
            ->latest()
            ->get();
    }

    public function findByLearnerAndProgram(int $learnerId, int $programId): ?Enrollment
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->first();
    }

    public function isEnrolled(int $learnerId, int $programId): bool
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->exists();
    }
}
