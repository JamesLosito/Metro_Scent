<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Admin Dashboard Summary
     */
    public function dashboard()
    {
        // Basic counts
        $usersCount = User::count();
        $productsCount = Product::count();
        
        // User distribution
        $adminUsersCount = User::where('is_admin', true)->count();
        $regularUsersCount = User::where('is_admin', false)->count();
        
        // User growth over time (last 6 months)
        $userGrowth = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // User activity (last 30 days)
        $activeUsers = Order::selectRaw('DATE(created_at) as date, COUNT(DISTINCT user_id) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Sales by product type
        $salesByType = Product::selectRaw('type, COUNT(*) as total_sales')
            ->groupBy('type')
            ->get();
            
        // Top selling products
        $topProducts = Product::withCount(['orderItems as sales' => function($query) {
                $query->whereHas('order', function($q) {
                    $q->where('status', 'processed');
                });
            }])
            ->orderBy('sales', 'desc')
            ->take(5)
            ->get();

        // Inventory stock report
        $lowStockProducts = Product::where('stock', '<', 10)
            ->orderBy('stock', 'asc')
            ->get();
            
        $stockByType = Product::selectRaw('type, SUM(stock) as total_stock')
            ->groupBy('type')
            ->get();

        // Enhanced Product Analytics
        $productPerformance = Product::selectRaw('
            type,
            COUNT(*) as total_products,
            AVG(price) as avg_price,
            SUM(stock) as total_stock,
            SUM(stock * price) as stock_value
        ')
        ->groupBy('type')
        ->get();

        // Stock Value Analysis
        $stockValue = Product::selectRaw('
            SUM(stock * price) as total_value,
            AVG(price) as avg_price,
            MAX(price) as max_price,
            MIN(price) as min_price
        ')
        ->first();

        // Product Category Insights
        $categoryInsights = Product::selectRaw('
            type,
            COUNT(*) as product_count,
            SUM(stock) as total_stock,
            AVG(price) as avg_price,
            SUM(stock * price) as inventory_value
        ')
        ->groupBy('type')
        ->get();

        // Stock Level Distribution
        $stockLevels = Product::selectRaw('
            CASE 
                WHEN stock = 0 THEN "Out of Stock"
                WHEN stock <= 5 THEN "Critical"
                WHEN stock <= 10 THEN "Low"
                WHEN stock <= 20 THEN "Medium"
                ELSE "Good"
            END as level,
            COUNT(*) as count
        ')
        ->groupBy('level')
        ->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'productsCount',
            'adminUsersCount',
            'regularUsersCount',
            'userGrowth',
            'activeUsers',
            'salesByType',
            'topProducts',
            'lowStockProducts',
            'stockByType',
            'productPerformance',
            'stockValue',
            'categoryInsights',
            'stockLevels'
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
                'type' => 'required|string|in:captivating,intense',
                'stock' => 'required|integer|min:0'
            ]);

            $data = [
                'name' => $request->name,
                'price' => $request->price,
                'description' => $request->description,
                'type' => strtolower($request->type),
                'stock' => $request->stock
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
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|string|in:captivating,intense',
            'stock' => 'required|integer|min:0'
        ]);

        $product = Product::where('product_id', $id)->firstOrFail();
        $data = $request->only('name', 'price', 'description', 'type', 'stock');
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
        $orders = Order::with('user', 'products')->get();
        return view('admin.orders', compact('orders'));
    }

    /**
     * Mark an order as processed
     */
    public function processOrder($orderId)
    {
        $order = Order::find($orderId);

        if ($order) {
            // Update order status and set delivery date
            $order->status = 'processed';
            $order->delivery_date = now(); // You can change this to a specific date logic if needed
            $order->save();

            // Redirect back to orders.blade.php with a success message
            return redirect()->route('admin.orders')->with('success', 'Order processed successfully!');
        }

        // Redirect if order not found
        return redirect()->route('admin.orders')->with('error', 'Order not found.');

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
}
