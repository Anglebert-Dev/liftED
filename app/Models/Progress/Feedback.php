<?php

namespace App\Models\Progress;

use App\Models\BaseModel;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Program\Program;
use App\Models\User;

class Feedback extends BaseModel
{
    protected $fillable = [
        'uuid',
        'mentor_id',
        'learner_id',
        'program_id',
        'material_id',
        'content',
    ];

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

    public function material()
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }
}
