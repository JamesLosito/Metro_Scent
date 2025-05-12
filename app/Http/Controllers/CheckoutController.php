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

        // Validate selected items and calculate total
        $selectedItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->selected_items)
            ->get();

        if ($selectedItems->isEmpty()) {
            throw new \Exception('No items selected for checkout.');
        }

        // Verify stock availability again
        foreach ($selectedItems as $item) {
            if ($item->product->stock < $item->quantity) {
                throw new \Exception("Item {$item->product->name} is out of stock.");
            }
        }

        // Calculate total
        $subtotal = $selectedItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $shippingFee = 50.00;
        $total = $subtotal + $shippingFee;

        // Create order
        $order = new Order();
        $order->user_id = Auth::id();
        $order->full_name = $request->full_name;
        $order->email = $request->email;
        $order->address = $request->address;
        $order->total = $total;
        $order->stripe_payment_intent = $paymentIntentId;
        $order->status = 'pending';
        
        if (!$order->save()) {
            throw new \Exception('Failed to save order');
        }

        // Create order items and update stock
        foreach ($selectedItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->quantity = $item->quantity;
            $orderItem->price = $item->product->price;
            
            if (!$orderItem->save()) {
                throw new \Exception('Failed to save order item');
            }

            // Update product stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        CartItem::where('user_id', Auth::id())->delete();

        DB::commit();
        
        // Send order confirmation email
        // TODO: Implement email sending
        
        return redirect()->route('checkout.success')
            ->with('success', 'Order placed successfully!')
            ->with('order_id', $order->id);

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

    // Check stock availability
    $outOfStockItems = $selectedItems->filter(function ($item) {
        return $item->product->stock < $item->quantity;
    });

    if ($outOfStockItems->isNotEmpty()) {
        $itemNames = $outOfStockItems->pluck('product.name')->join(', ');
        return redirect()->route('cart.index')
            ->with('error', "The following items are out of stock: {$itemNames}");
    }

    $shippingFee = 50.00;
    $subtotal = $selectedItems->sum(function ($item) {
        return $item->product->price * $item->quantity;
    });
    $total = $subtotal + $shippingFee;

    return view('checkout', [
        'selectedItems' => $selectedItems,
        'shippingFee' => $shippingFee,
        'subtotal' => $subtotal,
        'total' => $total
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

public function success()
{
    return view('checkout.success');
}

public function store(Request $request)
{
    $request->validate([
        'selected_items' => 'required|array|min:1',
        'selected_items.*' => 'integer|exists:cart_items,id',
    ]);
    // Redirect to GET /checkout with selected_items as input
    return redirect()->route('checkout', [
        'selected_items' => $request->input('selected_items')
    ]);
}

}