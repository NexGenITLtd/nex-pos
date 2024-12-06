<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\AppBaseController;
use App\Models\ProductCategory;
use App\Http\Requests\CategoryRequest;
use Spatie\Permission\Models\Permission;


class CategoryController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view categories')->only('index');
        $this->middleware('permission:create categories')->only('create', 'store');
        $this->middleware('permission:update categories')->only('edit', 'update');
        $this->middleware('permission:delete categories')->only('destroy');
    }

    /**
     * Display a listing of product categories.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $categories = ProductCategory::whereNull('parent_id')
            ->with('subcategories')
            ->paginate(10); // Adjusted pagination for better performance

        return view('products.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new product category.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = ProductCategory::whereNull('parent_id')->get();
        return view('products.categories.create', compact('categories'));
    }

    /**
     * Store a newly created product category.
     *
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        ProductCategory::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? null, // Default to 0 if parent_id is null
        ]);

        return redirect()
            ->route('categories.index')
            ->with('flash_success', $this->toastMessage('Product category successfully added.'));
    }

    /**
     * Show the form for editing the specified product category.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        $categories = ProductCategory::whereNull('parent_id')->get();

        return view('products.categories.edit', compact('category', 'categories'));
    }

    /**
     * Update the specified product category.
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? null, // Default to 0 if parent_id is null
        ]);

        return redirect()
            ->route('categories.edit', $category->id)
            ->with('flash_success', $this->toastMessage('Product category successfully updated.'));
    }

    /**
     * Remove the specified product category.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('flash_success', $this->toastMessage('Product category successfully deleted.'));
    }
}
