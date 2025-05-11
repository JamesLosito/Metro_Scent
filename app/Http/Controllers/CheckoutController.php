<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{

public function processCheckout(Request $request)
{
    DB::beginTransaction();

    try {
        $paymentIntentId = $request->input('payment_intent_id');

        if (!$paymentIntentId) {
            return redirect()->back()->with('error', 'Payment was not completed.');
        }

        // Verify payment from Stripe
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

        if ($paymentIntent->status !== 'succeeded') {
            return redirect()->back()->with('error', 'Payment failed.');
        }

        // Calculate total (trust but verify)
        $total = 0;
        foreach ($request->selected_items as $itemId) {
            $quantity = $request->input("item_quantity.$itemId");
            $price = $request->input("price.$itemId");
            $total += $quantity * $price;
        }

        // Save Order
        $order = Order::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'address' => $request->address,
            'total' => $total,
            'stripe_payment_intent' => $paymentIntentId, // optional for reference
        ]);

        foreach ($request->selected_items as $itemId) {
            OrderItem::create([
                'order_id' => $order->id,
                'cart_item_id' => $itemId,
                'quantity' => $request->input("item_quantity.$itemId"),
                'price' => $request->input("price.$itemId"),
            ]);
        }

        DB::commit();
        return redirect('/success')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Checkout failed: ' . $e->getMessage());
    }
}
}