<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\Store;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view store')->only('index','show');
        $this->middleware('permission:create store')->only('create', 'store');
        $this->middleware('permission:update store')->only('edit', 'update');
        $this->middleware('permission:delete store')->only('destroy');
    }
    public function index(){
        $stores = Store::orderBy('id')->paginate(10);
        return view("stores.index")->with(compact('stores'));
    }
    public function create()
    {
        return view("stores.create");
    }
    public function show() {
        
    }
    public function store(Request $request){
        if ($request->isMethod('post')) {
            $store = new Store();
            $store->name = $request->name;
            $store->phone = $request->phone;
            $store->email = $request->email;
            $store->printer_paper_size = $request->printer_paper_size;
            $store->address = $request->address;
            $store->return_policy = $request->return_policy;

            if ($request->hasFile('image')) {
                // image
                $extension = strtolower($request->file('image')->getClientOriginalExtension());
                $file_name = time().'.'.$extension;
                $image = Image::make($request->image)->resize(300, 300);
                $image->save('images/stores/'.$file_name);
                $store->logo = $file_name;
            }
            $store->save();
            return redirect()->route('stores.create')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Store successfully added`
                })
                </script>
                ');
        }
        
    }
    
    public function edit($id)
    {
        $store = Store::FindOrFail($id);
        return view("stores.edit")->with(compact('store'));
    }
    public function update(Request $request, $id){
        $store = Store::FindOrFail($id);
        
            $store->name = $request->name;
            $store->phone = $request->phone;
            $store->email = $request->email;
            $store->printer_paper_size = $request->printer_paper_size;
            $store->address = $request->address;
            $store->return_policy = $request->return_policy;

            if ($request->hasFile('image')) {
                // image
                if (file_exists('images/stores/'.$store->logo) && !empty($store->logo)) {
                    @unlink('images/stores/'.$store->logo);
                }
                $extension = strtolower($request->file('image')->getClientOriginalExtension());
                $file_name = time().'.'.$extension;
                $image = Image::make($request->image)->resize(300, 300);
                $image->save('images/stores/'.$file_name);
                $store->logo = $file_name;
            }
            $store->save();

            return redirect()->route('stores.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Store successfully added`
                })
                </script>
                ');
        
    }
    public function destroy($id)
    {
        if (!empty($id)) {
            $data = Store::FindOrFail($id);
            $image = 'images/stores/'.$data->logo;
            if (file_exists($image)) {
                @unlink($image);
            }
            Store::find($id)->delete();
            return redirect()->route('store.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Store successfully deleted`
                })
                </script>
                ');
        }
    }
}
