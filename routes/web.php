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
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.createPaymentIntent');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
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


Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/profile/edit', function () {
    return view('edit-profile'); // Create this view if needed
})->name('profile.edit');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'admin'])->group(function () {
    // Product Routes
    Route::get('/admin', [AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
    Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/admin/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // User Routes
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Orders
    Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
    Route::put('/admin/orders/{id}/process', [AdminController::class, 'processOrder'])->name('admin.orders.process');
});

use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');

