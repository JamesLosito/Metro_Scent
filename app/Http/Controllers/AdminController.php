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
        // COD orders
        $codOrders = Order::where('payment_method', 'cod')
            ->where('status', 'processed')
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->get();
        foreach ($codOrders as $order) {
            $order->status = 'delivered';
            $order->delivered_at = $now;
            $order->save();
        }
        // GCash orders
        $gcashOrders = Order::where('payment_method', 'gcash')
            ->where('status', 'processed')
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->get();
        foreach ($gcashOrders as $order) {
            $order->status = 'delivered';
            $order->delivered_at = $now;
            $order->save();
        }
        // Stripe orders
        $stripeOrders = Order::where('payment_method', 'stripe')
            ->whereIn('status', ['paid', 'processed'])
            ->whereNotNull('delivery_date')
            ->whereDate('delivery_date', '<=', $now->toDateString())
            ->get();
        foreach ($stripeOrders as $order) {
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
        
        // Update sales calculation to handle different payment methods
        $totalSales = Order::where(function($query) {
            // Stripe: count paid, processed, delivered
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            // GCash: count processed, delivered
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            // COD: only delivered
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })->sum('total');
        
        // Get sales data for the last 7 days with the same conditions
        $salesData = Order::where(function($query) {
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })
        ->where('created_at', '>=', now()->subDays(7))
        ->selectRaw('DATE(created_at) as date, SUM(total) as amount')
        ->groupBy('date')
        ->get();
            
        // Get monthly revenue data with the same conditions
        $monthlyRevenue = Order::where(function($query) {
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })
        ->where('created_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as amount')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Get product type distribution
        $categoryDistribution = Product::select('type', DB::raw('count(*) as count'))
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => ucfirst($item->type),
                    'count' => $item->count
                ];
            });

        // Get recent orders for timeline
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        // Get top selling products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->with(['product' => function($query) {
                $query->select('product_id', 'name');
            }])
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name,
                    'sales' => $item->total_quantity
                ];
            });

        // Get stock alerts
        $lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<=', 5)
            ->get();
        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->get();
            
        // Total sales
        $totalSales = Order::where(function($query) {
            // Stripe: count paid, processed, delivered
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            // GCash: count processed, delivered
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            // COD: only delivered
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })->sum('total');
        
        // Get sales data for the last 7 days with the same conditions
        $salesData = Order::where(function($query) {
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })
        ->where('created_at', '>=', now()->subDays(7))
        ->selectRaw('DATE(created_at) as date, SUM(total) as amount')
        ->groupBy('date')
        ->get();
            
        // Get monthly revenue data with the same conditions
        $monthlyRevenue = Order::where(function($query) {
            $query->where(function($q) {
                $q->where('payment_method', 'stripe')
                  ->whereIn('status', ['paid', 'processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'gcash')
                  ->whereIn('status', ['processed', 'delivered']);
            })
            ->orWhere(function($q) {
                $q->where('payment_method', 'cod')
                  ->where('status', 'delivered');
            });
        })
        ->where('created_at', '>=', now()->subMonths(6))
        ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, SUM(total) as amount')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

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
            'outOfStockProducts'
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
    public function markInTransit($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'processing') {
            $order->status = 'intransit';
            $order->save();

            return redirect()->back()->with('success', 'Order marked as In Transit.');
        }

        return redirect()->back()->with('error', 'Only processed orders can be marked as In Transit.');
    }
    public function markDelivered($id)
    {
        $order = Order::findOrFail($id);
        if ($order->status === 'intransit') {
            $order->status = 'delivered';
            $order->delivery_date = now(); // Optional
            $order->save();
            return redirect()->back()->with('success', 'Order marked as Delivered.');
        }
        return redirect()->back()->with('error', 'Only in-transit orders can be marked as delivered.');
    }

}
