<?php

namespace App\Providers;

use App\Helpers\AuthHelper;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Ngo;
use App\Models\Program\Program;
use App\Policies\LearningMaterial\LearningMaterialPolicy;
use App\Policies\Ngo\NgoPolicy;
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
        Gate::policy(Ngo::class, NgoPolicy::class);

        if (! class_exists('A')) {
            class_alias(AuthHelper::class, 'A');
        }
    }
}
