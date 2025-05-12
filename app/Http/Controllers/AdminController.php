<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Show all products
    public function showProducts()
    {
        $products = Product::all();
        return view('admin.products', compact('products'));
    }

    // Store a new product
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        Product::create($request->only('name', 'price', 'description'));

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    // Update an existing product
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->only('name', 'price', 'description'));

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    // Delete a product
    public function deleteProduct($id)
    {
        Product::destroy($id);
        return redirect()->back()->with('success', 'Product deleted.');
    }

    // Show all users
    public function showUsers()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users', compact('users'));
    }

    // Delete a user
    public function deleteUser($id)
    {
        User::destroy($id);
        return redirect()->back()->with('success', 'User removed.');
    }

    // Show all orders
    public function showOrders()
    {
        $orders = Order::with('user', 'products')->get();
        return view('admin.orders', compact('orders'));
    }

    // Process an order
    public function processOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'processed';
        $order->save();

        return redirect()->back()->with('success', 'Order marked as processed.');
    }
    public function dashboard()
{
    // example metrics
    $usersCount    = User::count();
    $productsCount = Product::count();
    $ordersPending = Order::where('status','pending')->count();

    return view('admin.dashboard', compact('usersCount','productsCount','ordersPending'));
}

}