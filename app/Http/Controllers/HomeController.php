<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except('index'); // allow guests to access homepage
    }

    /**
     * Show the application welcome page.
     */
    public function index()
    {
        return view('welcome', compact('bestSellers'));
    }
}
