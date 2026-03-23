<?php

namespace App\Models\LearningMaterial;

use App\Models\BaseModel;
use App\Models\Program\Program;

class LearningMaterial extends BaseModel
{
    protected $fillable = [
        'uuid',
        'title',
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
        return $this->hasMany(\App\Models\Progress\Progress::class, 'material_id');
    }


    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }
}
