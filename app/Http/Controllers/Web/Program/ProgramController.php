<?php

namespace App\Http\Controllers\Web\Program;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\Program\SaveProgramRequest;
use App\Models\Program\Program;
use App\Repositories\Program\ProgramRepository;
use App\Services\Program\ProgramService;

class ProgramController extends Controller
{
    public function __construct(
        private ProgramService    $service,
        private ProgramRepository $repo,
    ) {}

    public function index()
    {
        A::require('list programs.program');
        $programs = $this->repo->getPaginatedForNgo(auth()->user()->ngo_id);
        return view('program.index', compact('programs'));
    }

    public function show(Program $program)
    {
        A::require('read programs.program');
        $program = $this->repo->findByUuidWithRelations($program->uuid);
        return view('program.show', compact('program'));
    }

    public function create()
    {
        A::require('create programs.program');
        return view('program.edit', ['program' => null]);
    }

    public function store(SaveProgramRequest $request)
    {
        A::require('create programs.program');
        $result = $this->service->save($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }

    public function edit(Program $program)
    {
        A::require('update programs.program');
        return view('program.edit', compact('program'));
    }

    public function update(SaveProgramRequest $request, Program $program)
    {
        A::require('update programs.program');
        $result = $this->service->save($request, $program);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }

    public function destroy(Program $program)
    {
        A::require('delete programs.program');
        $result = $this->service->delete($program);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return $this->successRedirect('programs.index', $result['message']);
    }
}
