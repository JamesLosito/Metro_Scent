<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Admin Dashboard Summary
     */
    public function dashboard()
    {
        $usersCount    = User::count();
        $productsCount = Product::count();
        $ordersPending = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact('usersCount', 'productsCount', 'ordersPending'));
    }

    /**
     * Show all products
     */
    public function showProducts()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    /**
     * Store a new product
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($request->only('name', 'price', 'description'));

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'price', 'description'));

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product
     */
    public function deleteProduct($id)
    {
        Product::destroy($id);
        return redirect()->back()->with('success', 'Product deleted.');
    }

    /**
     * Show all non-admin users
     */
    public function showUsers()
    {
        // Get all non-admin users with valid IDs only
        $users = User::where('is_admin', '!=', '1')
                    ->whereNotNull('user_id') // just in case
                    ->get();

        // Optionally filter further if necessary (e.g., instance of User)
        $users = $users->filter(function ($users) {
            return isset($users->id);
        });

        return view('admin.users', compact('users'));
    }
    /**
     * Delete a user
     */
    public function deleteUser($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User removed.');
    }

    /**
     * Show all orders with user and product data
     */
    public function showOrders()
    {
        $orders = Order::with('users', 'products')->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * Mark an order as processed
     */
    public function processOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'processed';
        $order->save();

        return redirect()->back()->with('success', 'Order marked as processed.');
    }
}
