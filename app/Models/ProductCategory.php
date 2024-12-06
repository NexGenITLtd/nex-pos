<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class ProductCategory extends Model
{
    use HasRoles,HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id'];

    /**
     * Get the subcategories for the product category.
     */
    public function subcategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }

    /**
     * Get the parent category of the product category.
     */
    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }
}
