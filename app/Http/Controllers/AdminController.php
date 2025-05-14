<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Automatically mark COD and GCash orders as delivered if delivery date has passed
     */
    private function autoDeliverOrders()
    {
        $now = now();

        $orders = Order::whereIn('payment_method', ['cod', 'gcash', 'stripe'])
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->where(function ($query) {
                $query->whereIn('payment_method', ['cod', 'gcash'])->where('status', ['processed', 'intransit','delivered'])
                    ->orWhere(function ($q) {
                        $q->where('payment_method', 'stripe')->whereIn('status', ['processed', 'intransit','delivered']);
                    });
            })->get();

        foreach ($orders as $order) {
            $order->status = 'delivered';
            $order->delivered_at = $now;
            $order->save();
        }
    }

    /**
     * Admin Dashboard Summary
     */
    public function dashboard()
    {
        $this->autoDeliverOrders();

        $usersCount = User::count();
        $adminUsersCount = User::where('is_admin', true)->count();
        $regularUsersCount = User::where('is_admin', false)->count();
        $productsCount = Product::count();
        $ordersPending = Order::where('status', 'pending')->count();

        // Define base order query for sales
        $orderQuery = function ($query) {
            $query->where(function ($q) {
                $q->where('payment_method', 'stripe')
                    ->whereIn('status', ['processed', 'intransit','delivered']);
            })
            ->orWhere(function ($q) {
                $q->where('payment_method', 'gcash')
                    ->whereIn('status', ['processed', 'intransit','delivered']);
            })
            ->orWhere(function ($q) {
                $q->where('payment_method', 'cod')
                    ->where('status', ['processed', 'intransit','delivered']);
            });
        };

        $totalSales = Order::where($orderQuery)->sum('total');

        $salesData = Order::where($orderQuery)
            ->where('created_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, SUM(total) as amount')
            ->groupBy('date')
            ->get();

        $monthlyRevenue = Order::where($orderQuery)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $categoryDistribution = Product::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => ucfirst($item->type),
                    'count' => $item->count
                ];
            });

        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with(['product' => function ($query) {
                $query->select('product_id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'Unknown',
                    'sales' => $item->total_quantity
                ];
            });

        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->get();

        $outOfStockProducts = Product::where('stock', '<=', 0)->get();

        $orderStatusCounts = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->status => $item->count];
            });

        return view('admin.dashboard', compact(
            'usersCount',
            'adminUsersCount',
            'regularUsersCount',
            'productsCount',
            'ordersPending',
            'totalSales',
            'salesData',
            'monthlyRevenue',
            'categoryDistribution',
            'recentOrders',
            'topProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'orderStatusCounts'
        ));
    }

    /**
     * Show all products
     */
    public function showProducts(Request $request)
    {
        // Get the type filter from the request
        $type = $request->get('type', 'all');
        
        // Base query
        $query = Product::query();
        
        // Apply type filter if not 'all'
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        // Retrieve paginated products
        $products = $query->orderBy('product_id', 'asc')->paginate(10);
        
        // Get counts for each type
        $typeCounts = [
            'all' => Product::count(),
            'captivating' => Product::where('type', 'captivating')->count(),
            'intense' => Product::where('type', 'intense')->count()
        ];

        return view('admin.products', compact('products', 'typeCounts', 'type'));
    }

    /**
     * Store a new product
     */
    public function storeProduct(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'type' => 'required|string|in:captivating,intense'
            ]);

            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'type' => strtolower($request->type)
            ];
            
            if ($request->hasFile('image')) {
                $folder = 'products/' . $data['type'];
                $data['image'] = $request->file('image')->store($folder, 'public');
            }

            $product = Product::create($data);

            if ($product) {
                return redirect()->route('admin.products', ['type' => $data['type']])
                    ->with('success', 'Product added successfully.');
            }

            return redirect()->route('admin.products')
                ->with('error', 'Failed to add product. Please try again.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please check the form for errors.');
        } catch (\Exception $e) {
            Log::error('Product creation error: ' . $e->getMessage());
            return redirect()->route('admin.products')
                ->with('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing product
     */
    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|in:captivating,intense'
        ]);

        $product = Product::where('product_id', $id)->firstOrFail();
        $data = $request->only('name', 'price', 'stock', 'description', 'type');
        $data['type'] = strtolower($data['type']);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // Store new image in type-specific folder
            $folder = 'products/' . $data['type'];
            $data['image'] = $request->file('image')->store($folder, 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products', ['type' => $data['type']])
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Delete a product
     */
    public function deleteProduct($id)
    {
        $product = Product::where('product_id', $id)->firstOrFail();
        
        // Delete product image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->back()->with('success', 'Product deleted.');
    }

    /**
     * Show all users
     */
    public function showUsers()
    {
        $adminUsers = User::where('is_admin', true)->paginate(10);
        $regularUsers = User::where('is_admin', false)->paginate(10);
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
        $this->autoDeliverOrders();
        $orders = Order::with(['user', 'orderItems.product'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * Mark an order as processed
     */
    public function processOrder($orderId)
{
    $order = Order::find($orderId);

    if ($order && $order->status === 'pending') {
        $order->status = 'processed';

        // Set delivery date between 3 to 6 days from created_at
        $deliveryDays = rand(3, 6);
        $order->delivery_date = $order->created_at->copy()->addDays($deliveryDays);

        $order->save();

        return redirect()->route('admin.orders')->with('success', 'Order processed and delivery date assigned!');
    }

    return redirect()->route('admin.orders')->with('error', 'Order not found or cannot be processed.');
}
    public function markInTransit($id)
{
    $order = Order::findOrFail($id);

    if ($order->status !== 'processed') {
        return redirect()->back()->with('error', 'Order must be processed before marking as in-transit.');
    }

    $order->status = 'intransit';
    $order->save();

    return redirect()->back()->with('success', 'Order marked as in-transit.');
}

public function markDelivered($id)
{
    $order = Order::findOrFail($id);

    if ($order->status !== 'intransit') {
        return redirect()->back()->with('error', 'Order must be in transit before being marked as delivered.');
    }

    $order->status = 'delivered';
    $order->delivery_date = now();
    $order->save();

    return redirect()->back()->with('success', 'Order marked as delivered.');
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

    /**
     * Store a new user
     */
    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'is_admin' => 'required|boolean'
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'is_admin' => $request->boolean('is_admin'),
            ]);

            if ($user) {
                return redirect()->route('admin.users')
                    ->with('success', $request->boolean('is_admin') ? 'Admin user created successfully.' : 'Regular user created successfully.');
            }

            return redirect()->route('admin.users')
                ->with('error', 'Failed to create user. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')
                ->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                \Illuminate\Validation\Rule::unique('users', 'email')->ignore($id, 'user_id')
            ],
            'password' => 'nullable|string|min:8',
            'is_admin' => 'boolean'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'is_admin' => $request->boolean('is_admin')
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully.');
    }
    public function cancelOrder($orderId)
    {
        $order = \App\Models\Order::find($orderId);

        if (!$order) {
            return redirect()->route('admin.orders')->with('error', 'Order not found.');
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('admin.orders')->with('error', 'Order is already cancelled.');
        }

        // Optionally, prevent cancelling delivered orders
        if ($order->status === 'delivered') {
            return redirect()->route('admin.orders')->with('error', 'Cannot cancel a delivered order.');
        }

        $order->status = 'cancelled';
        $order->cancelled_at = now();
        $order->save();

        return redirect()->route('admin.orders')->with('success', 'Order #' . $orderId . ' has been cancelled.');
    }

}
