<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'image_url', 
        'category_id', 
        'material', 
        'weight', 
        'dimensions',
        'stock',
        'sku'
    ];

    // Check if product is out of stock
    public function isOutOfStock() {
        return $this->stock <= 0;
    }

    // Check if product is low in stock (less than or equal to 5 items)
    public function isLowStock() {
        return $this->stock > 0 && $this->stock <= 5;
    }

    // Get the category for this product
    public function category() {
        return $this->belongsTo(Category::class);
    }

    // Get all reviews for this product
    public function reviews() {
        return $this->hasMany(Review::class);
    }
    
    // Average rating
    public function averageRating() {
        $average = $this->reviews()->avg('rating');
        
        return round($average, 1);
    }

    // Gets count of specific star rating
    public function ratingCount($stars) {
        return $this->reviews()->where('rating', $stars)->count();
    }
    
    // Star rating
    public function starRating($stars) {
        $total = $this->reviews()->count();
        if ($total == 0) return 0;
        return round(($this->ratingCount($stars) / $total) * 100);
    }
    
}

