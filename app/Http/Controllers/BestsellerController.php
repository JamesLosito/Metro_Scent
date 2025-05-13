<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class BestSellerController extends Controller
{
    public function index()
    {
        // Get most ordered products (top 12)
        $mostOrdered = Product::select([
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image',
                'products.type',
                'products.is_best_seller',
                \DB::raw('SUM(order_items.quantity) as total_ordered')
            ])
            ->join('order_items', 'products.product_id', '=', 'order_items.product_id')
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
            ->take(12)
            ->get();

        // Get additional best sellers if not enough
        $bestSellerMarked = Product::where('is_best_seller', 1)
            ->whereNotIn('product_id', $mostOrdered->pluck('product_id'))
            ->take(12 - $mostOrdered->count())
            ->get();

        // Combine and remove duplicates
        $bestSellers = $mostOrdered->concat($bestSellerMarked);

        return view('bestseller', compact('bestSellers'));
    }
}
