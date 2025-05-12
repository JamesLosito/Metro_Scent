<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    // Display the user's cart items
    public function index()
    {
        // Ensure the user is authenticated
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to view your cart.');
        }
        
        // Fetch cart items for the authenticated user and eager load the associated product
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();

        return view('cart.index', compact('cartItems'));
    }

    // Add an item to the cart
    public function add(Request $request)
    {
        // Ensure the user is authenticated
        $userId = Auth::id();
        
        if (!$userId) {
            if ($request->ajax()) {
                return response()->json(['error' => 'You must be logged in to add items to your cart.'], 401);
            }
            return redirect()->route('login')->with('error', 'You must be logged in to add items to your cart.');
        }

        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id', // Ensure the product exists
        ]);

        $productId = $validated['product_id'];
        $product = Product::find($productId);

        // Check if product is in stock
        if ($product->stock <= 0) {
            if ($request->ajax()) {
                return response()->json(['error' => 'This product is out of stock.'], 400);
            }
            return back()->with('error', 'This product is out of stock.');
        }

        // Check if the product is already in the user's cart
        $existingItem = CartItem::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

        if ($existingItem) {
            // If the item already exists, increase the quantity
            $existingItem->increment('quantity', 1);
            if ($request->ajax()) {
                return response()->json(['message' => 'Item quantity updated in your cart!']);
            }
            return back()->with('message', 'Item quantity updated in your cart!');
        }

        // If the item doesn't exist, add it to the cart
        CartItem::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => 1,
        ]);

        if ($request->ajax()) {
            return response()->json(['message' => 'Item added to your cart!']);
        }
        return back()->with('message', 'Item added to your cart!');
    }

    // Remove an item from the cart
    public function remove(Request $request)
    {
        try {
            $itemId = $request->input('item_id');
            $cartItem = CartItem::where('id', $itemId)
                              ->where('user_id', auth()->id())
                              ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart item not found'
                ], 404);
            }

            $cartItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'Item removed from cart successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item from cart'
            ], 500);
        }
    }

    public function updateQuantity(Request $request)
{
    $request->validate([
        'item_id' => 'required|integer|exists:cart_items,id',
        'quantity' => 'required|integer|min:1',
    ]);

    $cartItem = CartItem::where('id', $request->item_id)
                        ->where('user_id', Auth::id())
                        ->with('product')
                        ->first();

    if (!$cartItem) {
        return response()->json(['success' => false, 'message' => 'Cart item not found.'], 404);
    }

    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    return response()->json([
        'success' => true,
        'new_total' => $cartItem->product->price * $cartItem->quantity,
        'message' => 'Quantity updated successfully.'
    ]);
}

}
