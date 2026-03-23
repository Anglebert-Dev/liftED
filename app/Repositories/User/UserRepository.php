<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function getByRole(string $role): Collection
    {
        return $this->model->byRole($role)->get();
    }

    public function getActiveByRole(string $role): Collection
    {
        return $this->model->active()->byRole($role)->get();
    }

    public function getByNgo(int $ngoId): Collection
    {
        return $this->model->where('ngo_id', $ngoId)->get();
    }

    public function getLearnersByNgo(int $ngoId): Collection
    {
        return $this->model->byRole('learner')->where('ngo_id', $ngoId)->get();
    }

    public function getMentorsByNgo(int $ngoId): Collection
    {
        return $this->model->byRole('mentor')->where('ngo_id', $ngoId)->get();
    }

    public function getAllPaginated(int $perPage = 20)
    {
        return $this->model->with('ngo')->latest()->paginate($perPage);
    }
}
