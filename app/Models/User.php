<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class);
    // }
    public function roles()
{
    return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
}

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'email',
        'img',
        'password',
    ];
    // Define the HasOne relationship to the Employee model
    public function employee()
    {
        return $this->hasOne(Employee::class, 'phone', 'phone');
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
