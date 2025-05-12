<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class BestsellerController extends Controller
{
    public function index()
    {
        // Get all products with their order counts
        $products = Product::select([
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image',
                'products.type',
                'products.is_best_seller',
                DB::raw('COALESCE(SUM(order_items.quantity), 0) as total_ordered')
            ])
            ->leftJoin('order_items', 'products.product_id', '=', 'order_items.product_id')
            ->groupBy(
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image',
                'products.type',
                'products.is_best_seller'
            )
            ->orderBy('total_ordered', 'desc')
            ->paginate(10);

        return view('admin.bestsellers.index', compact('products'));
    }

    public function toggleBestSeller(Request $request, Product $product)
    {
        $product->is_best_seller = !$product->is_best_seller;
        $product->save();

        return response()->json([
            'success' => true,
            'message' => $product->is_best_seller ? 'Product marked as bestseller' : 'Product unmarked as bestseller',
            'is_best_seller' => $product->is_best_seller
        ]);
    }
} 