<?php

namespace App\Http\Controllers\Backend;
use Auth;
use Image;
use App\Models\Store;
use App\Models\Asset;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AssetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view asset')->only('index');
        $this->middleware('permission:create asset')->only(['create', 'store']);
        $this->middleware('permission:update asset')->only(['edit', 'update']);
        $this->middleware('permission:delete asset')->only('destroy');
    }
    public function index(){
        $assets = Asset::with('store')->orderBy('id')->paginate(100);
        return view("assets.index")->with(compact('assets'));
    }
    public function create()
    {
        $stores = Store::get();
        return view("assets.create")->with(compact('stores'));
    }
    public function store(Request $request){
        if ($request->isMethod('post')) {
            $asset = new Asset();
            $asset->store_id = $request->store_id;
            $asset->name = $request->name;
            $asset->unit_price = $request->unit_price;
            $asset->qty = $request->qty;
            $asset->amount = $request->amount;
            $asset->purchase_date = $request->purchase_date;
            $asset->save();
            return redirect()->route('assets.create')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Asset successfully added`
                })
                </script>
                ');
        }
    }
    
    public function edit(Request $request, $id){
        $asset = Asset::FindOrFail($id);
        $stores = Store::get();
        return view("assets.edit")->with(compact('asset','stores'));
    }
    public function update(Request $request, $id){
        $asset = Asset::FindOrFail($id);
        if ($request->isMethod('post')) {
            $asset->store_id = $request->store_id;
            $asset->name = $request->name;
            $asset->unit_price = $request->unit_price;
            $asset->qty = $request->qty;
            $asset->amount = $request->amount;
            $asset->purchase_date = $request->purchase_date;
            $asset->save();
            return redirect()->route('assets.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Asset successfully updated`
                })
                </script>
                ');
        }
    }
    public function destroy($id)
    {
        if (!empty($id)) {
            Asset::find($id)->delete();
            return redirect()->route('assets.index')->with('flash_success','
                <script>
                Toast.fire({
                  icon: `success`,
                  title: `Asset successfully deleted`
                })
                </script>
                ');
        }
    }
}
