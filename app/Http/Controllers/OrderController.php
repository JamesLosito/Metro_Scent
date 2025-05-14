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
    public function markInTransit($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'intransit'; // or however you represent the status
        $order->save();

        return redirect()->back()->with('success', 'Order marked as in transit.');
    }
    public function markDelivered($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'delivered'; // or however you represent the status
        $order->save();

        return redirect()->back()->with('success', 'Order marked as delivered.');
    }
    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['intransit', 'delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'This order cannot be cancelled at this stage.');
        }

        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
}
