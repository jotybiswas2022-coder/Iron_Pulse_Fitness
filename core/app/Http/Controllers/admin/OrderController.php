<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Pack;

class OrderController extends Controller
{
    // ================== ORDER LIST ==================
    public function index()
    {
        $orders = Order::with(['orderdetails:id,order_id,pack_id,pack_name'])->latest()->get();
        return view('backend.order.index', compact('orders'));
    }

    // ================== APPROVE ==================
   public function approve(int $id)
{
    try {
        DB::transaction(function () use ($id) {

            $order = Order::with('orderdetails')->findOrFail($id);

            if (strtolower(trim($order->status)) !== 'pending') {
                abort(400, 'Order already processed');
            }

            // ✅ UPDATE WITH TIME
            $order->status = 'approved';
            $order->approved_at = now(); // 🔥 KEY LINE
            $order->save();

            $order->orderdetails()->update([
                'status' => 'approved'
            ]);
        });

        return response()->json([
            'success' => 'Order approved successfully'
        ]);

    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 400);
    }
}

    // ================== CANCEL ==================
    public function cancel(int $id)
    {
        try {
            DB::transaction(function () use ($id) {

                $order = Order::with('orderdetails')->findOrFail($id);

                // If approved, restore stock
                if (strtolower(trim($order->status)) === 'approved') {
                    foreach ($order->orderdetails as $item) {
                        Pack::where('id', $item->pack_id)
                            ->increment('stock', $item->pack_quantity);
                    }
                }

                $order->update([
                    'status' => 'canceled'
                ]);

                $order->orderdetails()->update([
                    'status' => 'canceled'
                ]);
            });

            return response()->json([
                'success' => 'Order canceled successfully'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}