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
            return redirect()->route('login')->with('error', 'You must be logged in to add items to your cart.');
        }

        // Validate the request data
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id', // Ensure the product exists
        ]);

        $productId = $validated['product_id'];

        // Check if the product is already in the user's cart
        $existingItem = CartItem::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

        if ($existingItem) {
            // If the item already exists, increase the quantity
            $existingItem->increment('quantity', 1);
            return back()->with('message', 'Item quantity updated in your cart!');
        }

        // If the item doesn't exist, add it to the cart
        CartItem::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => 1, // You can adjust this depending on your logic
        ]);

        return back()->with('message', 'Item added to your cart!');
    }

    // Remove an item from the cart
    public function remove($id)
    {
        // Ensure the user is authenticated
        $userId = Auth::id();
        
        if (!$userId) {
            return redirect()->route('login')->with('error', 'You must be logged in to remove items from your cart.');
        }

        // Fetch the cart item
        $item = CartItem::where('id', $id)->where('user_id', $userId)->first();

        if ($item) {
            // Delete the cart item
            $item->delete();
            return back()->with('message', 'Item removed from your cart.');
        }

        // If item doesn't exist, return an error message
        return back()->with('error', 'Item not found in your cart.');
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
