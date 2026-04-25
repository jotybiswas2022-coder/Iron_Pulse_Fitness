<?php
use App\Models\Cart;
use App\Models\Setting;
use App\Models\Product;

function cart($user_id = null) {
    $user_id = $user_id ?? auth()->id();
    if ($user_id) {
        return Cart::where('user_id', $user_id)->count();
    }
    return 0;
}

function IsAddedToCart($user_id, $product_id) {
    return Cart::where('user_id', $user_id)->where('product_id', $product_id)->exists(); 
}

function currency() {
    $settings = Setting::first(); 
    return $settings?->currency; 
}

function buyprice($id){
    return Product::find($id)->base_price;
}

