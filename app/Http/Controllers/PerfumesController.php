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

}
