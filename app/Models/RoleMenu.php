<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleMenu extends Model
{
    use HasFactory;
    protected $fillable = ['role_id', 'menu_id', 'can_create', 'can_edit', 'can_delete', 'can_view'];

    // Define the relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Define the relationship with Menu
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
