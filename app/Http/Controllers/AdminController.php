<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Retrieve all products from the database
        $products = Product::all();

        // Debugging step to ensure products are being fetched correctly
        // If products are not showing, add this temporarily for debugging
        // dd($products);

        // Ensure that products are passed to the view
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

        // Create new product
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

        // Find and update the product
        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'price', 'description'));

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product
     */
    public function deleteProduct($id)
    {
        // Delete the product
        Product::destroy($id);
        return redirect()->back()->with('success', 'Product deleted.');
    }

    /**
     * Show all users, separated into admin and regular users
     */
    public function showUsers()
    {
        $adminUsers = User::where('is_admin', 1)->get();
        $regularUsers = User::where('is_admin', 0)->get();
        return view('admin.users', compact('adminUsers', 'regularUsers'));
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

    /**
     * Show the admin's profile
     */
    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    /**
     * Show the form for editing the admin's profile
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the admin's profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')
            ],
        ]);

        User::where('user_id', $user->user_id)->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}
