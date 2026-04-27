<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Setting;

class OrderManageController extends Controller
{
    public function store(Request $request)
    {
        $user_id = auth()->id();

        $request->validate([
            'firstname'       => 'required|string|max:255',
            'lastname'        => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'required|string|max:20',
            'address'         => 'required|string|max:500',
            'payment_method'  => 'required|in:cod,bkash,nagad', 
        ]);

        $setting = Setting::first();
        $delivery_charge = $setting?->delivery_charge ?? 0;
        $tax_percentage  = $setting?->tax_percentage ?? 0;

        $carts = Cart::with('pack')->where('user_id', $user_id)->get();

        if ($carts->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $subtotal_after_discount = 0;

        foreach ($carts as $cart) {
            $pack = $cart->pack;

            if (!$pack) continue;

            $discount = $pack->discount ?? 0;
            $price_after_discount = $pack->pack_price * (100 - $discount) / 100;
            $subtotal_after_discount += $price_after_discount;
        }

        $tax_amount = ($subtotal_after_discount * $tax_percentage) / 100;
        $total_price = $subtotal_after_discount + $tax_amount + $delivery_charge;

        $order = Order::create([
            'user_id'                      => $user_id,
            'firstname'                    => $request->firstname,
            'lastname'                     => $request->lastname,
            'email'                        => $request->email,
            'phone'                        => $request->phone,
            'address'                      => $request->address,
            'pack_price_after_discount' => $subtotal_after_discount,
            'delivery_charge'              => $delivery_charge,
            'tax'                          => $tax_amount,
            'total_price'                  => $total_price,
            'payment_method'               => $request->payment_method,
        ]);

        foreach ($carts as $cart) {
            if (!$cart->pack) continue;

            OrderDetail::create([
                'order_id'         => $order->id,
                'pack_id'       => $cart->pack_id,
                'pack_name'     => $cart->pack->name,
                'pack_price'    => $cart->pack->pack_price,
                'status'           => 'processing',
            ]);
        }

        Cart::where('user_id', $user_id)->delete();

        return redirect('/cart')->with('success', 'Order Created Successfully!');
    }
}