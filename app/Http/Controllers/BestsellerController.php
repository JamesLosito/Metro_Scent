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
        // Get products ordered by the number of times they've been ordered
        $bestSellers = Product::select([
                'products.product_id',
                'products.name',
                'products.description',
                'products.price',
                'products.stock',
                'products.image',
                'products.type',
                'products.is_best_seller',
                DB::raw('SUM(order_items.quantity) as total_ordered')
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
            ->take(12) // Show top 12 bestsellers
            ->get();

        // If we don't have enough ordered products, add some marked as bestsellers
        if ($bestSellers->count() < 12) {
            $additionalProducts = Product::where('is_best_seller', 1)
                ->whereNotIn('product_id', $bestSellers->pluck('product_id'))
                ->take(12 - $bestSellers->count())
                ->get();
            
            $bestSellers = $bestSellers->concat($additionalProducts);
        }

        return view('bestseller', compact('bestSellers'));
    }
}
