<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'selected_items' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            // Create the order
            $order = Order::create([
                'full_name' => $validated['full_name'],
                'email' => $validated['email'],
                'address' => $validated['address'],
                'total' => 0 // will be updated after calculation
            ]);

            $grandTotal = 0;

            foreach ($validated['selected_items'] as $itemId) {
                $quantity = $request->input("item_quantity.$itemId");
                $price = $request->input("price.$itemId");
                $itemTotal = $quantity * $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'cart_item_id' => $itemId,
                    'quantity' => $quantity,
                    'price' => $price
                ]);

                $grandTotal += $itemTotal;
            }

            $order->update(['total' => $grandTotal]);

            DB::commit();

            // Redirect to a payment or success page
            return redirect()->route('payment.redirect', ['order_id' => $order->id]);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Checkout failed. Please try again.');
        }
    }
}
