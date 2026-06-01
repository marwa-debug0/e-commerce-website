<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * Apply a coupon to the user session.
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', $request->code)
            ->where('status', 'active')
            ->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid or inactive coupon code.');
        }

        // Store coupon details in the session
        session(['coupon' => [
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => $coupon->value,
            'min_spend' => $coupon->min_spend,
        ]]);

        return back()->with('success', 'Coupon applied successfully!');
    }

    /**
     * Remove the active coupon from the user session.
     */
    public function destroy()
    {
        session()->forget('coupon');

        return back()->with('success', 'Coupon removed.');
    }
}
