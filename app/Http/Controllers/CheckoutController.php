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
use Stripe\PaymentIntent;
use App\Models\Product;

class CheckoutController extends Controller
{
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
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount,
                'currency' => 'php',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'address' => $request->address
                ]
            ]);

            return response()->json([
                'client_secret' => $paymentIntent->client_secret
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'payment_method' => 'required|in:stripe,cod,gcash',
            'selected_items' => 'required|array',
            'selected_items.*' => 'exists:cart_items,id'
        ]);

        try {
            DB::beginTransaction();

            // Get selected cart items
            $selectedItems = CartItem::whereIn('id', $request->selected_items)
                ->where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($selectedItems->isEmpty()) {
                throw new \Exception('No items selected for checkout.');
            }

            // Calculate total
            $total = $selectedItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // Add shipping fee
            $total += 50;

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'full_name' => $request->full_name,
                'email' => $request->email,
                'address' => $request->address,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'stripe' ? 'paid' : 'pending'
            ]);

            // Create order items and update product stock
            foreach ($selectedItems as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'cart_item_id' => $item->id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price
                ]);

                // Update product stock
                $item->product->decrement('stock', $item->quantity);
            }

            // Handle Stripe payment
            if ($request->payment_method === 'stripe') {
                if (!$request->payment_intent_id) {
                    throw new \Exception('Payment intent ID is required for Stripe payments');
                }
                $order->stripe_payment_intent = $request->payment_intent_id;
            }

            // Clear cart items
            CartItem::whereIn('id', $request->selected_items)->delete();

            DB::commit();

            // Redirect to success page with the order
            return redirect()->route('checkout.success', ['order' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        // Verify the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        // Load the order with its items and products
        $order->load(['orderItems' => function($query) {
            $query->with('product');
        }]);
        
        return view('checkout.success', compact('order'));
    }

    /**
     * Verify stock availability for products in real-time
     */
    public function verifyStock(Request $request)
    {
        try {
            $request->validate([
                'product_ids' => 'required|array',
                'product_ids.*' => 'exists:products,product_id',
                'quantities' => 'required|array',
                'quantities.*' => 'required|integer|min:1'
            ]);

            $productIds = $request->product_ids;
            $quantities = $request->quantities;

            // Get current stock levels for all products
            $products = Product::whereIn('product_id', $productIds)->get();
            
            // Check stock for each product
            foreach ($products as $product) {
                $requestedQuantity = $quantities[$product->product_id] ?? 0;
                
                if ($product->stock < $requestedQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Insufficient stock for {$product->name}. Only {$product->stock} items available."
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Stock verification successful'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error verifying stock: ' . $e->getMessage()
            ], 500);
        }
    }
}