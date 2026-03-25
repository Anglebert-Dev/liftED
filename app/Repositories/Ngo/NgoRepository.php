<?php

namespace App\Repositories\Ngo;

use App\Models\Ngo;
use App\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class NgoRepository extends BaseRepository
{
    public function __construct(Ngo $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(int $perPage = 20): LengthAwarePaginator
    {
        return $this->model
            ->withCount(['users', 'programs'])
            ->latest()
            ->paginate($perPage);
    }
}
