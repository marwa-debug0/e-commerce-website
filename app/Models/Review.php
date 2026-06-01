<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Product;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
    ];
    
    // User who wrote the comment
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Product being reviewed
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
