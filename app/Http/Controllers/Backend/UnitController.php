<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Unit;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view create update delete unit')->only('index','show','create', 'store','edit', 'update','destroy');
        // $this->middleware('permission:create unit')->only();
        // $this->middleware('permission:update unit')->only();
        // $this->middleware('permission:delete unit')->only();
    }
    // Display a listing of units
    public function index()
    {
        $units = Unit::all();
        return view('units.index', compact('units'));
    }

    // Show the form for creating a new unit
    public function create()
    {
        return view('units.create');
    }

    // Store a newly created unit in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Unit::create([
            'name' => $request->name,
        ]);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    // Show the form for editing the specified unit
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    // Update the specified unit in storage
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $unit->update([
            'name' => $request->name,
        ]);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    // Remove the specified unit from storage
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
