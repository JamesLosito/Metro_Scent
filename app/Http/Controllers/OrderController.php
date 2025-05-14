<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function myOrders()
    {
        $orders = Order::where('user_id', auth()->id())->orderByDesc('created_at')->get();
        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('orders.details', compact('order'));
    }
    public function cancel($id)
    {
        $order = Order::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        if (in_array($order->status, ['pending', 'processing'])) {
            $order->status = 'cancelled';
            $order->save();

            return redirect()->back()->with('success', 'Order cancelled successfully.');
        }

        return redirect()->back()->with('error', 'This order cannot be cancelled.');
    }

}
