<?php

namespace App\Providers;

use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Program\Program;
use App\Policies\LearningMaterial\LearningMaterialPolicy;
use App\Policies\Program\ProgramPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policies
        Gate::policy(Program::class, ProgramPolicy::class);
        Gate::policy(LearningMaterial::class, LearningMaterialPolicy::class);

        if (! class_exists('A')) {
            class_alias(\App\Helpers\AuthHelper::class, 'A');
        }
    }
}
