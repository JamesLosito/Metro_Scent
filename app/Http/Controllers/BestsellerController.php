<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class BestSellerController extends Controller
{
    public function index()
    {
        $bestSellers = Product::where('is_best_seller', 1)->get();
        return view('bestseller', compact('bestSellers'));
    }
}
