<?php

namespace App\Services\Program;

use App\Models\Program\Program;
use App\Repositories\Program\ProgramRepository;
use App\Services\BaseService; 
use Illuminate\Http\Request;

class ProgramService extends BaseService
{
    public function __construct(private ProgramRepository $repo) {}

    public function save(Request $request, ?Program $program = null): array
    {
        try {
            $isNew   = is_null($program);
            $program = $program ?? new Program();

            $program->fill([
                'title'       => $request->input('title'),
                'description' => $request->input('description'),
                'ngo_id'      => auth()->user()->ngo_id,
                'is_active'   => $request->boolean('is_active', true),
            ]);

            $this->repo->save($program);

            return $this->success($program, $isNew ? 'Program created.' : 'Program updated.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save program: ' . $e->getMessage());
        }
    }

    public function delete(Program $program): array
    {
        try {
            $this->repo->delete($program);
            return $this->success(null, 'Program deleted.');
        } catch (\Throwable $e) {
            return $this->failure('Could not delete program.');
        }
    }
}
