<?php

namespace App\Models;

class Ngo extends BaseModel
{
    protected $table = 'ngos';

    protected $fillable = [
        'uuid',
        'name',
        'description',
        'user_id',
        'insert_by',
        'update_by',
        'delete_by',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function programs()
    {
        return $this->hasMany(\App\Models\Program\Program::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
