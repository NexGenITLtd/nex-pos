<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\Rack;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view rack')->only('index','show');
        $this->middleware('permission:create rack')->only('create', 'store');
        $this->middleware('permission:update rack')->only('edit', 'update');
        $this->middleware('permission:delete rack')->only('destroy');
    }
    public function index(){
        $racks = Rack::paginate(10);
        return view("racks.index")->with(compact('racks'));
    }
    public function create()
    {
        $stores = Store::all();  // Fetch all stores
        return view('racks.create', compact('stores'));
    }
    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            // Validate the request including store_id
            $validated = $request->validate([
                'name' => 'required|string|max:255',    // Validate name
                'store_id' => 'required|exists:stores,id', // Validate that store_id exists in the stores table
            ]);

            // Create a new rack and assign validated data
            $rack = new Rack();
            $rack->name = $validated['name'];
            $rack->store_id = $validated['store_id'];  // Add the store_id

            $rack->save();  // Save the rack to the database

            return redirect()->route('racks.create')->with('flash_success', '
                <script>
                    Toast.fire({
                        icon: `success`,
                        title: `Rack successfully added`
                    })
                </script>
            ');
        }
    }

    public function edit(Request $request, $id)
    {
        $rack = Rack::findOrFail($id); // Find the rack by id
        $stores = Store::all(); // Fetch all stores to display in the dropdown

        return view('racks.edit', compact('rack', 'stores')); // Pass rack and stores to the view
    }
    
    public function update(Request $request, $id)
    {
        // Validate the request including store_id
        $validated = $request->validate([
            'name' => 'required|string|max:255',    // Validate name
            'store_id' => 'required|exists:stores,id', // Ensure store_id exists in the stores table
        ]);

        // Find the rack by id
        $rack = Rack::findOrFail($id);

        // Update the rack with validated data
        $rack->name = $validated['name'];
        $rack->store_id = $validated['store_id'];  // Update store_id

        // Save the updated rack
        $rack->save();

        return redirect()->route('racks.index')->with('flash_success', '
            <script>
                Toast.fire({
                    icon: `success`,
                    title: `Rack successfully updated`
                })
            </script>
        ');
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            $data = Rack::FindOrFail($id);
            Rack::find($id)->delete();
            return redirect()->route('racks.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Rack successfully deleted`
                })
                </script>
                ');
        }
    }
}
