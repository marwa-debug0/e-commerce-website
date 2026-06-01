<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
    ];

    // Get the parent category
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    
    }

    //Get the subcategories
    public function subcategories() {
        return $this->hasMany(Category::class, 'parent_id');
    }

    //Get all products inside the category
    public function products() {
        return $this->hasMany(Product::class);
    }
}
