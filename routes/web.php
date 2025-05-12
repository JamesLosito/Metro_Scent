<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfumesController;
use App\Http\Controllers\BestsellerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Models\Product;
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
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.createPaymentIntent');
    Route::post('/process-checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.processCheckout');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

Route::prefix('perfumes')->group(function() {
    Route::get('captivating', [PerfumesController::class, 'captivating']);
    Route::get('intense', [PerfumesController::class, 'intense']);
});

Route::get('/view.product/{id}', [ProductController::class, 'show']);

Route::post('/send-message', [ContactController::class, 'send']);

Route::get('/redirect-to-payment', [PaymentController::class, 'redirectToStripe'])->name('payment.redirect');
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


