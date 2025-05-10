<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Correct placement of use statement

class PerfumesController extends Controller
{
    /**
     * Display a listing of perfumes.
     */
    public function index()
{
    $products = Product::all();
    return view('perfumes', compact('products')); // Not perfumes.index
}

// In PerfumesController.php

public function captivating()
{
    $products = Product::where('type', 'Captivating')
                        ->take(12) // gets products from index 0–11
                        ->get();

    return view('perfumes.captivating', compact('products'));
}

public function intense()
{
    $products = Product::where('type', 'Intense')
                        ->take(12) // gets products from index 0–11
                        ->get();

    return view('perfumes.intense', compact('products'));
}

}
