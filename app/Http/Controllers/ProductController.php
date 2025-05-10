<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; 

class ProductController extends Controller
{
    public function perfumes()
    {
        $products = Product::all();
        return view('perfumes', compact('products'));
    }
    
}
