<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Menu;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }



    public function showRoutes()
    {
        // $routes = Route::getRoutes();
        // foreach ($$routes as $key => $value) {
        //     Menu::create('')

        // }

        $routes = Route::getRoutes();

        // foreach ($routes as $route) {
        //     if ($route->getName()) {
        //         Menu::create([
        //             'name' => ucwords(str_replace('.', ' ', $route->getName())), // Human-readable name
        //             'route' => $route->getName(), // Save route name
        //             'icon' => null, // Add a default or specific icon if required
        //             'parent_id' => null, // Top-level menus for now
        //             'order' => 0, // Default order
        //         ]);
        //     }
        // }
        return view('routes.index', compact('routes'));
    }
}
