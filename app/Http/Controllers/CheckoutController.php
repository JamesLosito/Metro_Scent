<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{

public function processCheckout(Request $request)
{
    DB::beginTransaction();

    try {
        Log::info('Starting checkout process', ['request' => $request->all()]);

        $paymentIntentId = $request->input('payment_intent_id');

        if (!$paymentIntentId) {
            Log::error('No payment intent ID provided');
            return redirect()->back()->with('error', 'Payment was not completed.');
        }

        // Verify payment from Stripe
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

        if ($paymentIntent->status !== 'succeeded') {
            Log::error('Payment failed', ['status' => $paymentIntent->status]);
            return redirect()->back()->with('error', 'Payment failed.');
        }

        // Calculate total (trust but verify)
        $total = 0;
        $selectedItems = [];
        
        foreach ($request->selected_items as $itemId) {
            $quantity = $request->input("item_quantity.$itemId");
            $price = $request->input("price.$itemId");
            
            if (!$quantity || !$price) {
                Log::error('Missing quantity or price', [
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'price' => $price
                ]);
                throw new \Exception('Invalid item data');
            }
            
            $total += $quantity * $price;
            
            $selectedItems[] = [
                'id' => $itemId,
                'quantity' => $quantity,
                'price' => $price
            ];
        }

        // Add shipping fee to total
        $total += 50.00;

        Log::info('Creating order', [
            'total' => $total,
            'selected_items' => $selectedItems,
            'user_id' => Auth::id()
        ]);

        // Save Order
        $order = new Order();
        $order->user_id = Auth::id();
        $order->full_name = $request->full_name;
        $order->email = $request->email;
        $order->address = $request->address;
        $order->total = $total;
        $order->stripe_payment_intent = $paymentIntentId;
        
        if (!$order->save()) {
            Log::error('Failed to save order');
            throw new \Exception('Failed to save order');
        }

        Log::info('Order created', ['order_id' => $order->id]);

        // Save Order Items
        foreach ($selectedItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->cart_item_id = $item['id'];
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $item['price'];
            
            if (!$orderItem->save()) {
                Log::error('Failed to save order item', ['item' => $item]);
                throw new \Exception('Failed to save order item');
            }
        }

        Log::info('Order items created', ['order_id' => $order->id]);

        // Clear user's cart
        if (Auth::check()) {
            $deleted = CartItem::where('user_id', Auth::id())->delete();
            Log::info('Cart cleared for user', [
                'user_id' => Auth::id(),
                'items_deleted' => $deleted
            ]);
        }

        DB::commit();
        Log::info('Checkout completed successfully', ['order_id' => $order->id]);
        
        return redirect()->route('checkout.success')->with('success', 'Order placed successfully!');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Checkout failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return back()->with('error', 'Checkout failed: ' . $e->getMessage());
    }
}

public function checkout(Request $request)
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please login to checkout.');
    }

    $selectedItemIds = $request->input('selected_items', []);
    
    if (empty($selectedItemIds)) {
        return redirect()->route('cart.index')->with('error', 'Please select items to checkout.');
    }

    $selectedItems = CartItem::with('product')
        ->where('user_id', Auth::id())
        ->whereIn('id', $selectedItemIds)
        ->get();

    if ($selectedItems->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Selected items not found in your cart.');
    }

    $shippingFee = 50.00;

    return view('checkout', [
        'selectedItems' => $selectedItems,
        'shippingFee' => $shippingFee
    ]);
}

public function createPaymentIntent(Request $request)
{
    $amount = $request->input('amount');
    if (!$amount || $amount < 1) {
        return response()->json(['error' => 'Invalid amount'], 400);
    }

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    try {
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $amount, // amount in cents
            'currency' => 'php',
            'payment_method_types' => ['card'],
        ]);
        return response()->json(['client_secret' => $intent->client_secret]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}