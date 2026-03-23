<?php

use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\Enrollment\EnrollmentController;
use App\Http\Controllers\Web\LearningMaterial\LearningMaterialController;
use App\Http\Controllers\Web\Program\ProgramController;
use App\Http\Controllers\Web\Progress\ProgressController;
use App\Http\Controllers\Web\Role\RoleController;
use App\Http\Controllers\Web\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::middleware(['auth', 'approved'])->group(function () {

    // ── Dashboards (one per role) ──────────────────────────────────
    Route::get('/dashboard/superadmin', [DashboardController::class, 'superadmin'])->name('dashboard.superadmin');
    Route::get('/dashboard/ngo-staff',  [DashboardController::class, 'ngoStaff'])->name('dashboard.ngo_staff');
    Route::get('/dashboard/mentor',     [DashboardController::class, 'mentor'])->name('dashboard.mentor');
    Route::get('/dashboard/learner',    [DashboardController::class, 'learner'])->name('dashboard.learner');

    // ── Programs ──────────────────────────────────────────────────
    Route::resource('programs', ProgramController::class);

    // ── Learning Materials (nested under programs) ─────────────────
    Route::prefix('programs/{program}/materials')->name('programs.materials.')->group(function () {
        Route::get('/',           [LearningMaterialController::class, 'index'])->name('index');
        Route::get('/create',     [LearningMaterialController::class, 'create'])->name('create');
        Route::post('/',          [LearningMaterialController::class, 'store'])->name('store');
        Route::get('/{material}', [LearningMaterialController::class, 'serve'])->name('serve');
        Route::get('/{material}/edit',   [LearningMaterialController::class, 'edit'])->name('edit');
        Route::put('/{material}',        [LearningMaterialController::class, 'update'])->name('update');
        Route::delete('/{material}',     [LearningMaterialController::class, 'destroy'])->name('destroy');
    });

    // ── Enrollments (nested under programs) ────────────────────────
    Route::prefix('programs/{program}/enrollments')->name('programs.enrollments.')->group(function () {
        Route::get('/',                    [EnrollmentController::class, 'index'])->name('index');
        Route::get('/create',              [EnrollmentController::class, 'create'])->name('create');
        Route::post('/',                   [EnrollmentController::class, 'store'])->name('store');
        Route::get('/{enrollment}/edit',   [EnrollmentController::class, 'edit'])->name('edit');
        Route::put('/{enrollment}',        [EnrollmentController::class, 'update'])->name('update');
        Route::delete('/{enrollment}',     [EnrollmentController::class, 'destroy'])->name('destroy');
    });

    // ── Progress & Feedback ────────────────────────────────────────
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/{program}/{learner}', [ProgressController::class, 'show'])->name('progress.show');
    Route::post('/progress/feedback', [ProgressController::class, 'storeFeedback'])->name('progress.feedback.store');

    // ── Users (SuperAdmin) ─────────────────────────────────────────
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/ban',     [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban',   [UserController::class, 'unban'])->name('users.unban');

    // ── Roles & Permissions (SuperAdmin) ───────────────────────────
    Route::resource('roles', RoleController::class)->except(['show']);
});
