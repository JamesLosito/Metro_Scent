<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfumesController;
use App\Http\Controllers\BestsellerController;
use App\Models\Product;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\CartItem;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');  // Make sure resources/views/welcome.blade.php exists
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/perfumes', [PerfumesController::class, 'index']);
Route::get('/bestseller', [BestsellerController::class, 'index'])->name('bestseller');
Route::view('/aboutus', 'aboutus')->name('aboutus');
Route::view('/contact', 'contact')->name('contact');

Route::get('/view_product/{id}', [ProductController::class, 'show'])->name('product.view');



Route::get('/home', function () {
    $products = Product::all();
    return view('home', compact('products'));
})->name('home');



Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
});

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::post('/checkout', function (Request $request) {
    $selectedIds = $request->input('selected_items', []);
    $selectedItems = CartItem::with('product')->whereIn('id', $selectedIds)->get();

    if ($selectedItems->isEmpty()) {
        return redirect('/cart')->with('error', 'No items selected.');
    }

    return view('checkout', compact('selectedItems'));
});

Route::post('/process-checkout', function (Request $request) {
    // You would typically save order and clear cart here
    return redirect('/cart')->with('success', 'Order placed successfully!');
});