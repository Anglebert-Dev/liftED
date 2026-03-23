<?php

namespace App\Policies\LearningMaterial;

use App\Helpers\AuthHelper as A;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\User;

class LearningMaterialPolicy
{
    public function viewAny(User $user): bool
    {
        return A::can('list programs.material');
    }

    public function view(User $user, LearningMaterial $material): bool
    {
        if (! A::can('read programs.material')) {
            return false;
        }
        // Learners must be enrolled
        if ($user->role === 'learner') {
            return \App\Models\Enrollment\Enrollment::where('learner_id', $user->id)
                ->where('program_id', $material->program_id)
                ->exists();
        }
        return true;
    }

    public function create(User $user): bool
    {
        return A::can('upload programs.material');
    }

    public function update(User $user, LearningMaterial $material): bool
    {
        return A::can('update programs.material');
    }

    public function delete(User $user, LearningMaterial $material): bool
    {
        return A::can('delete programs.material');
    }
}
