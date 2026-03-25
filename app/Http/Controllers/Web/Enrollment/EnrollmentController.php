<?php

namespace App\Http\Controllers\Web\Enrollment;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Enrollment\SaveEnrollmentRequest;
use App\Models\Enrollment\Enrollment;
use App\Models\Program\Program;
use App\Repositories\Enrollment\EnrollmentRepository;
use App\Repositories\User\UserRepository;
use App\Services\Enrollment\EnrollmentService;

class EnrollmentController extends Controller
{
    public function __construct(
        private EnrollmentService    $service,
        private EnrollmentRepository $repo,
        private UserRepository       $userRepo,
    ) {}

    public function indexAll()
    {
        A::require('list learners.enrollment');

        if (A::can('view all programs')) {
            $enrollments = $this->repo->getAll();
        } elseif (auth()->user()->ngo_id) {
            $enrollments = $this->repo->getByNgo((int) auth()->user()->ngo_id);
        } else {
            abort(403);
        }

        return view('enrollment.index-all', compact('enrollments'));
    }

    public function index(Program $program)
    {
        A::require('list learners.enrollment');
        $enrollments = $this->repo->getByProgram($program->id);
        return view('enrollment.index', compact('program', 'enrollments'));
    }

    public function create(Program $program)
    {
        A::require('create learners.enrollment');
        $ngoId   = auth()->user()->ngo_id;
        $learners = $this->userRepo->getLearnersByNgo($ngoId);
        $mentors  = $this->userRepo->getMentorsByNgo($ngoId);
        return view('enrollment.edit', compact('program', 'learners', 'mentors', ));
    }

    public function store(SaveEnrollmentRequest $request, Program $program)
    {
        A::require('create learners.enrollment');
        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.enrollments.index', $program)->with('success', $result['message']);
    }

    public function edit(Program $program, Enrollment $enrollment)
    {
        A::require('update learners.enrollment');
        $ngoId   = auth()->user()->ngo_id;
        $learners = $this->userRepo->getLearnersByNgo($ngoId);
        $mentors  = $this->userRepo->getMentorsByNgo($ngoId);
        return view('enrollment.edit', compact('program', 'enrollment', 'learners', 'mentors'));
    }

    public function update(SaveEnrollmentRequest $request, Program $program, Enrollment $enrollment)
    {
        A::require('update learners.enrollment');
        $result = $this->service->save($request, $enrollment);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.enrollments.index', $program)->with('success', $result['message']);
    }

    public function destroy(Program $program, Enrollment $enrollment)
    {
        A::require('delete learners.enrollment');
        $result = $this->service->delete($enrollment);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.enrollments.index', $program)->with('success', $result['message']);
    }
}
