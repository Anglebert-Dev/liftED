<?php

namespace App\Models\Progress;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\Program\Program;

class Feedback extends BaseModel
{
    protected $fillable = [
        'uuid',
        'mentor_id',
        'learner_id',
        'program_id',
        'content',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
