<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\User;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'total_price',
        'status',

        // Shipping details
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'postal_code',

        // Discount coupon
        'coupon_code',
        'discount_amount',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}
