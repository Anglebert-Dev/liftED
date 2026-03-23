<?php

namespace App\Http\Controllers\Web\LearningMaterial;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\LearningMaterial\SaveMaterialRequest;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Program\Program;
use App\Repositories\LearningMaterial\LearningMaterialRepository;
use App\Services\LearningMaterial\LearningMaterialService;
use App\Services\Progress\ProgressService;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    public function __construct(
        private LearningMaterialService    $service,
        private LearningMaterialRepository $repo,
        private ProgressService            $progressService,
    ) {}

    public function index(Program $program)
    {
        A::require('list programs.material');
        $materials = $this->repo->getByProgram($program->id);
        return view('material.index', compact('program', 'materials'));
    }

    public function create(Program $program)
    {
        A::require('upload programs.material');
        return view('material.edit', ['program' => $program, 'material' => null]);
    }

    public function store(SaveMaterialRequest $request, Program $program)
    {
        A::require('upload programs.material');
        $result = $this->service->saveBatch($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }

    public function edit(Program $program, LearningMaterial $material)
    {
        A::require('update programs.material');
        return view('material.edit', compact('program', 'material'));
    }

    public function update(SaveMaterialRequest $request, Program $program, LearningMaterial $material)
    {
        A::require('update programs.material');
        $result = $this->service->save($request, $material);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }


    public function destroy(Program $program, LearningMaterial $material)
    {
        A::require('delete programs.material');
        $result = $this->service->delete($material);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }

    
    public function serve(Program $program, LearningMaterial $material)
    {
        A::require('read programs.material');

        $user = auth()->user();

        if ($user->role === 'learner') {
            $enrolled = \App\Models\Enrollment\Enrollment::where('learner_id', $user->id)
                ->where('program_id', $program->id)
                ->exists();

            if (! $enrolled) {
                abort(403, 'You are not enrolled in this program.');
            }

            $this->progressService->logView($user->id, $material->id, $program->id);
        }

        if (! Storage::disk('local')->exists($material->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('local')->download($material->file_path, $material->title);
    }
}
