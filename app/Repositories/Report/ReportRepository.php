<?php

namespace App\Repositories\Report;

use App\Models\Enrollment\Enrollment;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Progress\Progress;
use App\Models\Program\Program;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class ReportRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    public function onboardedLearnersCount(?int $ngoId = null): int
    {
        return (int) $this->model
            ->when($ngoId, fn ($q) => $q->whereHas('program', fn ($p) => $p->where('ngo_id', $ngoId)))
            ->distinct('learner_id')
            ->count('learner_id');
    }

    public function enrollmentsCount(?int $ngoId = null): int
    {
        return (int) $this->model
            ->when($ngoId, fn ($q) => $q->whereHas('program', fn ($p) => $p->where('ngo_id', $ngoId)))
            ->count();
    }

    public function mentorsHelpedCount(?int $ngoId = null): int
    {
        return (int) $this->model
            ->when($ngoId, fn ($q) => $q->whereHas('program', fn ($p) => $p->where('ngo_id', $ngoId)))
            ->whereNotNull('mentor_id')
            ->distinct('mentor_id')
            ->count('mentor_id');
    }

    public function programIds(?int $ngoId = null): Collection
    {
        return Program::query()
            ->when($ngoId, fn ($q) => $q->where('ngo_id', $ngoId))
            ->pluck('id');
    }

    public function materialsTotalCount(Collection $programIds): int
    {
        if ($programIds->isEmpty()) {
            return 0;
        }

        return (int) LearningMaterial::query()
            ->whereIn('program_id', $programIds)
            ->count();
    }

    public function materialsCompletedCount(Collection $programIds): int
    {
        if ($programIds->isEmpty()) {
            return 0;
        }

        return (int) Progress::query()
            ->whereIn('program_id', $programIds)
            ->where('completion_status', 'completed')
            ->count();
    }

    public function learnersCompletedAnyCount(Collection $programIds): int
    {
        if ($programIds->isEmpty()) {
            return 0;
        }

        return (int) Progress::query()
            ->whereIn('program_id', $programIds)
            ->where('completion_status', 'completed')
            ->distinct('learner_id')
            ->count('learner_id');
    }

    public function learnersCompletedAllMaterialsCount(?int $ngoId = null): int
    {
        $programs = Program::query()
            ->select('programs.id')
            ->when($ngoId, fn ($q) => $q->where('programs.ngo_id', $ngoId));

        $materialsPerProgram = LearningMaterial::query()
            ->select('program_id', DB::raw('COUNT(*) as total_materials'))
            ->whereIn('program_id', $programs)
            ->groupBy('program_id');

        $completedPerLearnerProgram = Progress::query()
            ->select('learner_id', 'program_id', DB::raw("COUNT(*) as completed_materials"))
            ->where('completion_status', 'completed')
            ->groupBy('learner_id', 'program_id');

        $count = DB::query()
            ->fromSub(
                Enrollment::query()
                    ->select('learner_id', 'program_id')
                    ->distinct()
                    ->when($ngoId, fn ($q) => $q->whereHas('program', fn ($p) => $p->where('ngo_id', $ngoId))),
                'e'
            )
            ->joinSub($materialsPerProgram, 'm', 'm.program_id', '=', 'e.program_id')
            ->leftJoinSub($completedPerLearnerProgram, 'c', function ($join) {
                $join->on('c.learner_id', '=', 'e.learner_id')
                    ->on('c.program_id', '=', 'e.program_id');
            })
            ->where('m.total_materials', '>', 0)
            ->whereRaw('COALESCE(c.completed_materials, 0) >= m.total_materials')
            ->distinct()
            ->count('e.learner_id');

        return (int) $count;
    }

    public function staffCount(?int $ngoId = null): int
    {
        return (int) User::query()
            ->when($ngoId, fn ($q) => $q->where('ngo_id', $ngoId))
            ->whereHas('roles', fn ($q) => $q->where('name', 'NGO Staff'))
            ->count();
    }

    public function mentorCount(?int $ngoId = null): int
    {
        return (int) User::query()
            ->when($ngoId, fn ($q) => $q->where('ngo_id', $ngoId))
            ->whereHas('roles', fn ($q) => $q->where('name', 'Mentor'))
            ->count();
    }

    public function learnerCount(?int $ngoId = null): int
    {
        return (int) User::query()
            ->when($ngoId, fn ($q) => $q->where('ngo_id', $ngoId))
            ->whereHas('roles', fn ($q) => $q->where('name', 'Learner'))
            ->count();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function programSummaries(?int $ngoId = null): Collection
    {
        $programs = Program::query()
            ->select(['id', 'title'])
            ->when($ngoId, fn ($q) => $q->where('ngo_id', $ngoId))
            ->orderBy('title')
            ->get();

        if ($programs->isEmpty()) {
            return collect();
        }

        $programIds = $programs->pluck('id');

        $materialsByProgram = LearningMaterial::query()
            ->select('program_id', DB::raw('COUNT(*) as total'))
            ->whereIn('program_id', $programIds)
            ->groupBy('program_id')
            ->pluck('total', 'program_id');

        $onboardedByProgram = Enrollment::query()
            ->select('program_id', DB::raw('COUNT(DISTINCT learner_id) as total'))
            ->whereIn('program_id', $programIds)
            ->groupBy('program_id')
            ->pluck('total', 'program_id');

        $completedByProgram = Progress::query()
            ->select('program_id', DB::raw("COUNT(*) FILTER (WHERE completion_status = 'completed') as completed"))
            ->whereIn('program_id', $programIds)
            ->groupBy('program_id')
            ->pluck('completed', 'program_id');

        return $programs->map(function ($p) use ($materialsByProgram, $onboardedByProgram, $completedByProgram) {
            return [
                'program' => $p,
                'learners_onboarded' => (int) ($onboardedByProgram[$p->id] ?? 0),
                'materials_total' => (int) ($materialsByProgram[$p->id] ?? 0),
                'materials_completed' => (int) ($completedByProgram[$p->id] ?? 0),
            ];
        });
    }
}

