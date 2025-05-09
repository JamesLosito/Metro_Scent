<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\PerfumesController;

Route::get('/', function () {
    $bestSellers = Product::take(5)->get(); // Get first 5 products (adjust as needed)
    return view('welcome', compact('bestSellers'));
});
Route::get('/welcome', function () {
    return view('welcome');
});



Route::get('/bestseller', function () {
    return view('bestseller');
});

Route::get('/aboutus', function () {
    return view('aboutus');
});

Route::get('/contact', function () {
    return view('contact');
});
use App\Http\Controllers\ProductController;

Route::get('/perfumes', [ProductController::class, 'perfumes']);

use App\Http\Controllers\CartController;

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');

use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup']);

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// routes/web.php

use App\Http\Controllers\Auth\RegisterController;

Route::get('/signup', [RegisterController::class, 'show'])->name('register');
Route::post('/signup', [RegisterController::class, 'register']);
