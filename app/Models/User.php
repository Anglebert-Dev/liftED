<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, HasRoles;

    protected $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'email',
        'phone_number',
        'role',
        'password',
        'ngo_id',
        'is_approved',
        'banned_at',
        'insert_by',
        'update_by',
        'delete_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at'         => 'datetime',
            'is_approved'       => 'boolean',
            'password'          => 'hashed',
            'deleted_at'        => 'datetime',
        ];
    }


    public function ngo()
    {
        return $this->belongsTo(Ngo::class);
    }


    public function isBanned(): bool
    {
        return ! is_null($this->banned_at);
    }

    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    public function fullName(): string
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    public function roleLabel(): string
    {
        $name = $this->getRoleNames()->first();

        if ($name) {
            return (string) $name;
        }

        return str_replace('_', ' ', ucfirst((string) $this->role));
    }

    public function scopeByRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('banned_at')->where('is_approved', true);
    }
}
