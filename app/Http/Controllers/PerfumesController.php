<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Make sure this model exists

class PerfumesController extends Controller
{
    /**
     * Create a new controller instance.
     */
    use App\Models\Product;
    public function index()
    {
        $products = Product::all(); // or paginate if needed
        return view('perfumes.index', compact('products'));
    }

    public function perfumes()
    {
        $products = Product::all(); // Fetch all products from DB
        return view('perfumes', compact('product')); // Pass to view
    }
}
