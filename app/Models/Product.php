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
        'dimensions'
    ];

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

