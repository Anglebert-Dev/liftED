<?php

namespace App\Repositories\Progress;

use App\Models\Progress\Feedback;
use App\Models\Progress\Progress;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class ProgressRepository extends BaseRepository
{
    public function __construct(Progress $model)
    {
        parent::__construct($model);
    }

    public function findOrCreateForLearnerMaterial(int $learnerId, int $materialId, int $programId): Progress
    {
        $progress = $this->model->withTrashed()
            ->where('learner_id', $learnerId)
            ->where('material_id', $materialId)
            ->where('program_id', $programId)
            ->first();

        if ($progress) {
            if ($progress->trashed()) {
                $progress->restore();
            }

            return $progress;
        }

        return $this->model->create([
            'learner_id' => $learnerId,
            'material_id' => $materialId,
            'program_id' => $programId,
        ]);
    }

    public function getAllForLearnerInProgram(int $learnerId, int $programId): Collection
    {
        return $this->model
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->with(['material'])
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
            ->with(['material'])
            ->get();
    }


    public function saveFeedback(array $data): Feedback
    {
        return Feedback::updateOrCreate(
            [
                'mentor_id'   => $data['mentor_id'],
                'learner_id'  => $data['learner_id'],
                'program_id'  => $data['program_id'],
                'material_id' => $data['material_id'] ?? null,
            ],
            ['content' => $data['content']]
        );
    }

 
    public function getFeedbackMapForLearnerProgram(int $learnerId, int $programId, int $authorUserId): SupportCollection
    {
        return Feedback::query()
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->where('mentor_id', $authorUserId)
            ->get()
            ->keyBy(fn (Feedback $f) => $f->material_id === null ? 'program' : (string) $f->material_id);
    }

    /** Latest note per scope across all authors (mentors, staff, etc.). */
    public function getFeedbackMapAggregateForLearnerProgram(int $learnerId, int $programId): SupportCollection
    {
        $rows = Feedback::query()
            ->where('learner_id', $learnerId)
            ->where('program_id', $programId)
            ->with('material')
            ->orderByDesc('updated_at')
            ->get();

        $map = collect();
        foreach ($rows as $f) {
            $key = $f->material_id === null ? 'program' : (string) $f->material_id;
            if (! $map->has($key)) {
                $map->put($key, $f);
            }
        }

        return $map;
    }
}
