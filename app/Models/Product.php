<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'product_category_id', 'product_sub_category_id', 'brand_id', 'unit', 'status'];

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }
    public function subcategory(){
        return $this->belongsTo(ProductCategory::class, 'product_sub_category_id');
    }
    public function brand(){
        return $this->belongsTo(ProductBrand::class);
    }
    public function stockIns()
	{
	    return $this->hasMany(StockIn::class, 'product_id');
	}
	public function sellProducts()
	{
	    return $this->hasMany(SellProduct::class, 'product_id');
	}
    public function returnSellProducts()
    {
        return $this->hasMany(ReturnSellProduct::class, 'product_id');
    }
}
