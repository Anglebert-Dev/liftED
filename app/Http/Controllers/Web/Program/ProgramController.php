<?php

namespace App\Http\Controllers\Web\Program;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Program\SaveProgramRequest;
use App\Models\Program\Program;
use App\Repositories\Program\ProgramRepository;
use App\Services\Program\ProgramService;
use Illuminate\Support\Facades\Gate;

class ProgramController extends Controller
{
    public function __construct(
        private ProgramService $service,
        private ProgramRepository $repo,
    ) {}

    public function index()
    {
        A::require('list programs.program');
        $user = auth()->user();
        $programs = A::can('read learners.own_progress')
            ? $this->repo->getPaginatedForLearner($user->id, $user->ngo_id)
            : $this->repo->getPaginatedForNgo($user->ngo_id);

        return view('program.index', compact('programs'));
    }

    public function show(Program $program)
    {
        A::require('read programs.program');
        Gate::authorize('view', $program);
        $program = $this->repo->findByUuidWithRelations($program->uuid) ?? abort(404);

        return view('program.show', compact('program'));
    }

    public function create()
    {
        A::require('create programs.program');
        Gate::authorize('create', Program::class);

        return view('program.edit', ['program' => null]);
    }

    public function store(SaveProgramRequest $request)
    {
        A::require('create programs.program');
        Gate::authorize('create', Program::class);
        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }

    public function edit(Program $program)
    {
        A::require('update programs.program');
        Gate::authorize('update', $program);

        return view('program.edit', compact('program'));
    }

    public function update(SaveProgramRequest $request, Program $program)
    {
        A::require('update programs.program');
        Gate::authorize('update', $program);
        $result = $this->service->save($request, $program);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }

    public function destroy(Program $program)
    {
        A::require('delete programs.program');
        Gate::authorize('delete', $program);
        $result = $this->service->delete($program);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }
}
