<?php

namespace App\Models;

use BezhanSalleh\FilamentShield\Support\Utils;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(){
        return $this->morphToMany(Role::class, 'model', 'model_has_roles', 'model_id', 'role_id');
    }

    public function canAccessPanel(): bool
    {
        return $this->hasRole(Utils::getSuperAdminName()) || $this->hasRole(Utils::getPanelUserRoleName());
    }
}
