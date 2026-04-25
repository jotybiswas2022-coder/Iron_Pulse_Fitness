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
        $orders = Order::with('orderdetails')->latest()->get();
        return view('backend.order.index', compact('orders'));
    }

    // ================== APPROVE ==================
    public function approve(int $id)
    {
        try {
            DB::transaction(function () use ($id) {

                $order = Order::with('orderdetails')->findOrFail($id);

                // Normalize status check
                if (strtolower(trim($order->status)) !== 'pending') {
                    abort(400, 'Order already processed');
                }

                // Stock check
                foreach ($order->orderdetails as $item) {
                    $pack = Pack::find($item->pack_id);

                    if (!$pack) {
                        abort(400, 'pack not found');
                    }
                }

                // Deduct stock
                foreach ($order->orderdetails as $item) {
                    Pack::where('id', $item->pack_id)
                        ->decrement('stock', $item->pack_quantity);
                }

                // Update order
                $order->update([
                    'status' => 'approved'
                ]);

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

    // ================== DELIVERED ==================
    public function delivered(int $id)
    {
        try {
            DB::transaction(function () use ($id) {

                $order = Order::with('orderdetails')->findOrFail($id);

                if (strtolower(trim($order->status)) !== 'approved') {
                    abort(400, 'Only approved orders can be delivered');
                }

                $order->update([
                    'status' => 'delivered'
                ]);

                $order->orderdetails()->update([
                    'status' => 'delivered'
                ]);
            });

            return response()->json([
                'success' => 'Order delivered successfully'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}