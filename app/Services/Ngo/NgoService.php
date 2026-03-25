<?php

namespace App\Services\Ngo;

use App\Models\Ngo;
use App\Repositories\Ngo\NgoRepository;
use App\Services\BaseService;
use Illuminate\Http\Request;

class NgoService extends BaseService
{
    public function __construct(private NgoRepository $repo) {}

    public function save(Request $request, ?Ngo $ngo = null): array
    {
        try {
            $model = $ngo ?? new Ngo;
            $model->name = $request->input('name');
            $model->description = $request->input('description');

            $this->repo->save($model);

            return $this->success($model, $ngo ? 'NGO updated.' : 'NGO created.');
        } catch (\Throwable $e) {
            return $this->failure('Could not save NGO: '.$e->getMessage());
        }
    }

    public function delete(Ngo $ngo): array
    {
        try {
            if ($ngo->programs()->exists() || $ngo->users()->exists()) {
                return $this->failure('Cannot delete an NGO that still has programs or users. Reassign them first.');
            }

            $this->repo->delete($ngo);

            return $this->success(null, 'NGO deleted.');
        } catch (\Throwable $e) {
            return $this->failure('Could not delete NGO.');
        }
    }
}
