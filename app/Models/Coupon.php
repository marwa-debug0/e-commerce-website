<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'status',
        'min_spend',
    ];

    // Calculate discount amount based on order total
    public function calculateDiscount($orderTotal) {
        if ($orderTotal < $this->min_spend) {
            return 0;
        }
        if ($this->type === 'percent') {
            return round(($this->value / 100) * $orderTotal, 2);
        }

        // Fixed amount
        return min($this->value, $orderTotal);
    }
    
}
