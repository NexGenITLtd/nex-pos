<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleMenu;
class RoleCheck
{

    public function handle($request, Closure $next, $role)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if the user's role matches one of the allowed roles
        // if (!in_array($user->role, explode('|', $role))) {
        //     return redirect()->route('unauthorized'); // Redirect if the user doesn't have the correct role
        // }
        // Check menu-based permissions
        // $currentRoute = $request->route()->getName();
        // $hasAccess = RoleMenu::where('role_id', $user->role_id)
        //     ->whereHas('menu', function ($query) use ($currentRoute) {
        //         $query->where('route', $currentRoute);
        //     })
        //     ->exists();

        // if (!$hasAccess) {
        //     // return redirect()->route('unauthorized');
        //     return abort(403, 'Access Denied');
        // }

        return $next($request);
    }
}
