<?php

namespace App\Models\Program;

use App\Models\BaseModel;
use App\Models\Ngo;
use App\Models\LearningMaterial\LearningMaterial;
use App\Models\Enrollment\Enrollment;

class Program extends BaseModel
{
    protected $fillable = [
        'uuid',
        'title',
        'description',
        'ngo_id',
        'is_active',
        'insert_by',
        'update_by',
        'delete_by',
    ];

    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'is_active' => 'boolean',
        ]);
    }


    public function ngo()
    {
        return $this->belongsTo(Ngo::class);
    }

    public function learningMaterials()
    {
        return $this->hasMany(LearningMaterial::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }


    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForNgo($query, int $ngoId)
    {
        return $query->where('ngo_id', $ngoId);
    }
}
