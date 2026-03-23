<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

abstract class BaseModel extends Model
{
    use SoftDeletes;

    protected $keyType = 'int';
    public $incrementing = true;

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            if (auth()->check()) {
                $user = auth()->user();
                $model->insert_by = $user->id . '|' . $user->firstname . ' ' . $user->lastname;
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                $model->update_by = $user->id . '|' . $user->firstname . ' ' . $user->lastname;
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $user = auth()->user();
                $model->delete_by = $user->id . '|' . $user->firstname . ' ' . $user->lastname;
                $model->saveQuietly();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
        ];
    }
}
