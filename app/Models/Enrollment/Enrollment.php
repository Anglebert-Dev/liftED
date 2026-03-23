<?php

namespace App\Models\Enrollment;

use App\Models\BaseModel;
use App\Models\User;
use App\Models\Program\Program;

class Enrollment extends BaseModel
{
    protected $fillable = [
        'uuid',
        'learner_id',
        'program_id',
        'mentor_id',
        'enrolled_at',
        'insert_by',
        'update_by',
        'delete_by',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'enrolled_at' => 'datetime',
        ]);
    }

    // ── Relationships ──────────────────────────────────────────────

    public function learner()
    {
        return $this->belongsTo(User::class, 'learner_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
