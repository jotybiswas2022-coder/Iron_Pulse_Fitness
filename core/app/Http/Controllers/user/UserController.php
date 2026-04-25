<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Product;

class UserController extends Controller
{
    function cart(){
        $subtotal = 0;
        $discount = 0;
        $delivery = 150; 
        return view('frontend.user.cart', compact('subtotal', 'discount', 'delivery'));
    }

    function manage($type, $id){
        if($type == 'plus') {
            $cart = Cart::findOrFail($id);
            $product_id = $cart->product_id;
            $stock = Product::find($product_id)->stock;
            if($stock == $cart->quantity){
                return redirect()->back()->with('error', 'Out of Stock!');
            } 
            $cart->update(['quantity' => $cart->quantity + 1]);
        } elseif($type == 'minus') {
            $cart = Cart::findOrFail($id);
            if($cart->quantity > 1) {
                $cart->update(['quantity' => $cart->quantity - 1]);
            } else {
                $cart->delete();
            }
        } elseif($type == 'destroy') {
            Cart::findOrFail($id)->delete();
        } else {
            return abort(404);
        }
        return redirect('/cart')->with('success', 'Cart Updated Successfully!');
    }

    public function addcart($product_id)
    {
        $user_id = auth()->user()->id;

        Cart::create([
            'user_id'   => $user_id,
            'product_id'=> $product_id,
            'quantity'  => 1
        ]);

        return redirect('/product/'.$product_id)->with('success', 'Added to Cart Successfully');
    }

    function billing(){
        return view('frontend.user.billing');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->with('orderDetails')
                       ->latest()
                       ->get();
        return view('frontend.user.orders', compact('orders'));
    }

    public function contactus(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        Contact::create($request->all());

        return back()->with('success', 'Message sent successfully!');
    }
}