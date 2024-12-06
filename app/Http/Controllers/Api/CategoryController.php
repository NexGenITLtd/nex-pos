<?php

namespace App\Http\Controllers\Api;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function categories(){
        return $categories = ProductCategory::whereNull('parent_id')->get();
    }
    public function subcategories($id){
        return $categories = ProductCategory::where('parent_id',$id)->get();
    }
}
