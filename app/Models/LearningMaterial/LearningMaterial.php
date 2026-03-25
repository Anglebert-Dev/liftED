<?php

namespace App\Models\LearningMaterial;

use App\Models\BaseModel;
use App\Models\Program\Program;
use App\Models\Progress\Progress;

class LearningMaterial extends BaseModel
{
    protected $fillable = [
        'uuid',
        'title',
        'description',
        'external_url',
        'type',
        'file_path',
        'program_id',
        'insert_by',
        'update_by',
        'delete_by',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function progressRecords()
    {
        return $this->hasMany(Progress::class, 'material_id');
    }

    public function getFileExtensionAttribute(): string
    {
        if (blank($this->file_path)) {
            return '';
        }

        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function hasStoredFile(): bool
    {
        return filled($this->file_path);
    }

    public function hasExternalUrl(): bool
    {
        return filled($this->external_url);
    }
}
