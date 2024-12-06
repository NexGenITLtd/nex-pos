<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Role extends Model
{
    use HasRoles;
    // Define the relationship with RoleMenu
    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
    protected $fillable = [
        'name',
    ];
 //    public function users()
	// {
	//     return $this->hasMany(User::class);
	// }
    public function users()
{
    return $this->belongsToMany(User::class, 'role_user', 'role_id', 'user_id');
}

}

