<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $menuName, $action)
    {
        $user = Auth::user();

        // Get the role_id from the user
        $roleId = $user->role_id;

        // Find the menu_id based on menu name (you can use route or other fields)
        $menu = DB::table('menus')->where('route', $menuName)->first();

        if (!$menu) {
            abort(404, 'Menu not found');
        }

        // Check for permissions in the role_menus table
        $permission = DB::table('role_menus')
            ->where('role_id', $roleId)
            ->where('menu_id', $menu->id)
            ->first();

        // If no permission or no specific action permission
        if (!$permission || !$permission->$action) {
            abort(403, 'You do not have permission for this action');
        }

        return $next($request);
    }
}
