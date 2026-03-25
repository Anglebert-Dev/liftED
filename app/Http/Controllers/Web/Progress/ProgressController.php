<?php

namespace App\Http\Controllers\Web\Progress;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Progress\SaveFeedbackRequest;
use App\Models\Program\Program;
use App\Models\User;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\Progress\ProgressRepository;
use App\Services\Progress\ProgressService;
use Illuminate\Support\Facades\Gate;

class ProgressController extends Controller
{
    public function __construct(
        private ProgressService $service,
        private ProgressRepository $repo,
        private EnrollmentRepository $enrollmentRepo,
    ) {}

    public function index()
    {
        A::require('list learners.progress');
        $enrollments = $this->enrollmentsForProgressList();

        return view('progress.index', [
            'enrollments' => $enrollments,
            'orgWide' => $this->userSeesNgoWideProgress(),
            'globalScope' => A::can('view all programs'),
        ]);
    }

    public function show(Program $program, User $learner)
    {
        A::require('read learners.progress');
        $this->authorizeProgressAccess($program, $learner);

        $progressRecords = $this->repo->getWithStats($learner->id, $program->id);
        $feedbackMaterialOptions = $program->learningMaterials()->orderBy('title')->pluck('title', 'id')->all();

        $aggregate = $this->userSeesNgoWideProgress();
        $feedbackByMaterial = $aggregate
            ? $this->repo->getFeedbackMapAggregateForLearnerProgram($learner->id, $program->id)
            : $this->repo->getFeedbackMapForLearnerProgram($learner->id, $program->id, auth()->id());
        $feedbackForForm = $this->repo->getFeedbackMapForLearnerProgram($learner->id, $program->id, auth()->id());

        return view('progress.show', compact(
            'program',
            'learner',
            'progressRecords',
            'feedbackByMaterial',
            'feedbackForForm',
            'feedbackMaterialOptions',
            'aggregate',
        ));
    }

    public function storeFeedback(SaveFeedbackRequest $request)
    {
        A::require('update learners.progress');
        $program = Program::findOrFail($request->input('program_id'));
        $learner = User::findOrFail($request->input('learner_id'));
        $this->authorizeProgressAccess($program, $learner);

        $result = $this->service->saveFeedback($request, auth()->id());

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function learnerShow(Program $program)
    {
        A::require('read learners.own_progress');
        Gate::authorize('view', $program);

        $user = auth()->user();
        $progressRecords = $this->repo->getWithStats($user->id, $program->id);
        $feedbackByMaterial = $this->repo->getFeedbackMapAggregateForLearnerProgram($user->id, $program->id);

        return view('progress.learner-show', compact('program', 'progressRecords', 'feedbackByMaterial'));
    }

   
    private function userSeesNgoWideProgress(): bool
    {
        if (A::can('view all programs')) {
            return true;
        }

        return A::can('list learners.enrollment') && (bool) auth()->user()->ngo_id;
    }

    private function enrollmentsForProgressList()
    {
        if (A::can('view all programs')) {
            return $this->enrollmentRepo->getAll();
        }

        if ($this->userSeesNgoWideProgress()) {
            return $this->enrollmentRepo->getByNgo((int) auth()->user()->ngo_id);
        }

        return $this->enrollmentRepo->getByMentor(auth()->id());
    }

    private function authorizeProgressAccess(Program $program, User $learner): void
    {
        $enrollment = $this->enrollmentRepo->findByLearnerAndProgram($learner->id, $program->id);
        abort_unless($enrollment, 404);

        if (A::can('view all programs')) {
            return;
        }

        $user = auth()->user();

        if (A::can('list learners.enrollment') && $user->ngo_id
            && (int) $program->ngo_id === (int) $user->ngo_id
            && (int) $learner->ngo_id === (int) $user->ngo_id) {
            return;
        }

        if ((int) $enrollment->mentor_id === (int) $user->id) {
            return;
        }

        abort(403);
    }
}
