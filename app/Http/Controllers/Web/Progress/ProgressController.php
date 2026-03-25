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
        $enrollments = $this->enrollmentRepo->getByMentor(auth()->id());

        return view('progress.index', compact('enrollments'));
    }

    public function show(Program $program, User $learner)
    {
        A::require('read learners.progress');
        $progressRecords = $this->repo->getWithStats($learner->id, $program->id);
        $feedback = $this->repo->getFeedbackForLearnerInProgram($learner->id, $program->id);

        return view('progress.show', compact('program', 'learner', 'progressRecords', 'feedback'));
    }

    public function storeFeedback(SaveFeedbackRequest $request)
    {
        A::require('update learners.progress');
        $result = $this->service->saveFeedback($request);

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
        $feedback = $this->repo->getFeedbackForLearnerInProgram($user->id, $program->id);

        return view('progress.learner-show', compact('program', 'progressRecords', 'feedback'));
    }
}
