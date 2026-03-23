<?php

namespace App\Models\Progress;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Program\Program;

class Progress extends BaseModel
{
    protected $fillable = [
        'learner_id',
        'material_id',
        'program_id',
        'viewed_at',
        'downloaded_at',
        'completion_status',
    ];

    // Progress does not need UUID or audit fields by default
    // but still soft-deletes via BaseModel
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'viewed_at'     => 'datetime',
            'downloaded_at' => 'datetime',
        ]);
    }

    // ── Relationships ──────────────────────────────────────────────

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function material()
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
