<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\AppBaseController;
use App\Models\ProductBrand;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use Spatie\Permission\Models\Permission;

class BrandController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view product-brand')->only('index');
        $this->middleware('permission:create product-brand')->only(['create', 'store']);
        $this->middleware('permission:update product-brand')->only(['edit', 'update']);
        $this->middleware('permission:delete product-brand')->only('destroy');
    }

    public function index()
    {
        $brands = ProductBrand::paginate(500);
        return view("products.brands.index", compact('brands'));
    }

    public function create()
    {
        return view("products.brands.create");
    }

    public function store(StoreBrandRequest $request)
    {
        ProductBrand::create(['name' => $request->name]);

        return redirect()
            ->route('brands.index')
            ->with('flash_success', $this->toastMessage('Brand successfully added.'));
    }


    public function edit($id)
    {
        $brand = ProductBrand::findOrFail($id);
        return view('products.brands.edit', compact('brand'));
    }


    public function update(StoreBrandRequest $request, $id)
    {
        $brand = ProductBrand::findOrFail($id);
        $brand->update($request->only('name'));

        return redirect()
            ->route('brands.edit', $brand->id)
            ->with('flash_success', $this->toastMessage('Brand successfully updated.'));
    }

    public function destroy($id)
    {
        $brand = ProductBrand::findOrFail($id);
        $brand->delete();

        return redirect()
            ->route('brands.index')
            ->with('flash_success', $this->toastMessage('Brand successfully deleted.'));
    }

}
