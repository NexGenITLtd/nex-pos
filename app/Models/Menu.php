<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    // Define the relationship with RoleMenu
    public function roleMenus()
    {
        return $this->hasMany(RoleMenu::class);
    }
    // Relationship to get children of a menu item
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    // Relationship to get the parent of a menu item
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function getCanViewAttribute()
    {
        return auth()->user()->hasPermissionTo('view-menu-' . strtolower(str_replace(' ', '_', $this->name)));
    }
    protected $fillable = ['name', 'route', 'icon', 'parent_id','order'];
}
