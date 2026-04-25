<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class ProfitController extends Controller
{
    public function index()
    {
        $orders = Order::where('status', 'delivered')->with('orderdetails')->get();
        return view('backend.profit_loss', compact('orders'));
    }
}
