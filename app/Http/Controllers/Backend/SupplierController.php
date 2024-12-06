<?php

namespace App\Http\Controllers\Backend;

use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\SupplierPaymentAlert;
use App\Models\StockIn;
use App\Http\Controllers\Controller;
use App\Helpers\ImageUploadHelper;
use App\Http\Requests\StoreSupplierRequest;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view supplier')->only('index','show');
        $this->middleware('permission:create supplier')->only('create', 'store');
        $this->middleware('permission:update supplier')->only('edit', 'update');
        $this->middleware('permission:delete supplier')->only('destroy');
    }

    public function index()
    {
        if (request()->ajax()) {
            $suppliers = Supplier::select(['id', 'name', 'contact_person', 'phone', 'email', 'address', 'img']);
            return DataTables::of($suppliers)
                ->addColumn('image', function ($row) {
                    $img = $row->img 
                        ? asset('images/suppliers/' . $row->img) 
                        : asset('images/default.png');
                    return '<img src="' . $img . '" height="60" width="60" alt="Supplier Image">';
                })
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('suppliers.edit', $row->id) . '" class="btn btn-primary btn-sm">Edit</a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $row->id . '">Delete</button>
                        <form id="delete-form-' . $row->id . '" action="' . route('suppliers.destroy', $row->id) . '" method="POST" style="display:none;">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    ';
                })
                ->rawColumns(['image', 'action']) // Render HTML for these columns
                ->make(true);
        }

        return view('suppliers.index');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        // Create supplier instance and assign data
        $supplier = new Supplier($request->only(['name', 'contact_person', 'phone', 'email', 'address']));

        // Handle image upload if present
        $supplier->img = $request->hasFile('image') ? 
            ImageUploadHelper::uploadImage($request, 'image', 'suppliers', 300, 300) : null;

        // Save the supplier data
        $supplier->save();

        return redirect()->route('suppliers.create')->with('success', 'Supplier successfully added');
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(StoreSupplierRequest $request, $id)
    {
        $supplier = Supplier::findOrFail($id);
        
        // Update supplier data
        $supplier->fill($request->only(['name', 'contact_person', 'phone', 'email', 'address']));

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete the old image
            if ($supplier->img) {
                ImageUploadHelper::deleteImage($supplier->img, 'suppliers');
            }

            // Upload the new image
            $supplier->img = ImageUploadHelper::uploadImage($request, 'image', 'suppliers', 300, 300);
        }

        // Save the updated supplier data
        $supplier->save();

        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully updated');
    }

    public function show() {
        
    }
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Delete related records and images
        $this->deleteRelatedData($supplier);
        $this->deleteSupplierImage($supplier);

        // Delete the supplier record
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier successfully deleted');
    }

    // Helper method to delete related data
    private function deleteRelatedData($supplier)
    {
        SupplierPayment::where('supplier_id', $supplier->id)->delete();
        SupplierPaymentAlert::where('supplier_id', $supplier->id)->delete();
        StockIn::where('supplier_id', $supplier->id)->delete();
    }

    // Helper method to delete supplier image
    private function deleteSupplierImage($supplier)
    {
        if ($supplier->img) {
            ImageUploadHelper::deleteImage($supplier->img, 'suppliers');
        }
    }
}
