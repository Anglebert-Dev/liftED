<?php

namespace App\Http\Controllers\Web\LearningMaterial;

use App\Helpers\AuthHelper as A;
use App\Http\Controllers\Controller;
use App\Http\Requests\LearningMaterial\SaveMaterialRequest;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Program\Program;
use App\Models\Progress\Progress;
use App\Repositories\LearningMaterial\LearningMaterialRepository;
use App\Services\LearningMaterial\LearningMaterialService;
use App\Services\Progress\ProgressService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class LearningMaterialController extends Controller
{
    public function __construct(
        private LearningMaterialService $service,
        private LearningMaterialRepository $repo,
        private ProgressService $progressService,
    ) {}

    public function index(Program $program)
    {
        A::require('list programs.material');
        Gate::authorize('view', $program);
        $materials = $this->repo->getByProgram($program->id);

        $progressByMaterialId = [];
        if (auth()->user()->role === 'learner') {
            $progressByMaterialId = Progress::query()
                ->where('learner_id', auth()->id())
                ->where('program_id', $program->id)
                ->whereIn('material_id', $materials->pluck('id'))
                ->get()
                ->keyBy('material_id')
                ->all();
        }

        return view('material.index', compact('program', 'materials', 'progressByMaterialId'));
    }

    public function create(Program $program)
    {
        A::require('upload programs.material');
        Gate::authorize('view', $program);

        return view('material.edit', ['program' => $program, 'material' => null]);
    }

    public function store(SaveMaterialRequest $request, Program $program)
    {
        A::require('upload programs.material');
        Gate::authorize('view', $program);

        $result = $request->input('creation_mode') === 'link'
            ? $this->service->saveLinkMaterial($request)
            : $this->service->saveBatch($request);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }

    public function edit(Program $program, LearningMaterial $material)
    {
        A::require('update programs.material');
        Gate::authorize('view', $program);
        $this->assertMaterialInProgram($program, $material);

        return view('material.edit', compact('program', 'material'));
    }

    public function update(SaveMaterialRequest $request, Program $program, LearningMaterial $material)
    {
        A::require('update programs.material');
        Gate::authorize('view', $program);
        $this->assertMaterialInProgram($program, $material);

        $result = $this->service->save($request, $material);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }

    public function destroy(Program $program, LearningMaterial $material)
    {
        A::require('delete programs.material');
        Gate::authorize('view', $program);
        $this->assertMaterialInProgram($program, $material);

        $result = $this->service->delete($material);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return redirect()->route('programs.materials.index', $program)->with('success', $result['message']);
    }

    public function visit(Program $program, LearningMaterial $material)
    {
        A::require('read programs.material');
        Gate::authorize('view', $program);
        Gate::authorize('view', $material);
        $this->assertMaterialInProgram($program, $material);

        if (! $material->hasExternalUrl()) {
            abort(404);
        }

        $url = $material->external_url;
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            abort(403, 'Invalid link.');
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));
        if (! in_array($scheme, ['http', 'https'], true)) {
            abort(403, 'Invalid link.');
        }

        if (auth()->user()->role === 'learner') {
            $this->progressService->logView(auth()->id(), $material->id, $program->id);
        }

        return redirect()->away($url);
    }

    public function markComplete(Program $program, LearningMaterial $material)
    {
        A::require('read programs.material');
        Gate::authorize('view', $program);
        Gate::authorize('view', $material);
        $this->assertMaterialInProgram($program, $material);

        $user = auth()->user();
        if ($user->role !== 'learner') {
            abort(403);
        }

        $result = $this->progressService->markCompleteByLearner($user->id, $material->id, $program->id);

        if (! $result['status']) {
            return $this->errorRedirect($result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function serve(Program $program, LearningMaterial $material)
    {
        A::require('read programs.material');
        Gate::authorize('view', $program);
        Gate::authorize('view', $material);
        $this->assertMaterialInProgram($program, $material);

        if ($material->hasStoredFile() && Storage::disk('local')->exists($material->file_path)) {
            $user = auth()->user();
            if ($user->role === 'learner') {
                $this->progressService->logView($user->id, $material->id, $program->id);
                $this->progressService->logDownload($user->id, $material->id, $program->id);
            }

            return Storage::disk('local')->download($material->file_path, $material->title);
        }

        if ($material->hasExternalUrl()) {
            return redirect()->route('programs.materials.visit', [$program, $material]);
        }

        abort(404, 'File not found.');
    }

    private function assertMaterialInProgram(Program $program, LearningMaterial $material): void
    {
        if ((int) $material->program_id !== (int) $program->id) {
            abort(404);
        }
    }
}
