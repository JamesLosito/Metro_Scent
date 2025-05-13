<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\CartItem;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfumesController;
use App\Http\Controllers\BestsellerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';

// General Routes
Route::get('/home', function () {
    $products = Product::all();

    $mostOrdered = Product::select([
        'products.product_id',
        'products.name',
        'products.description',
        'products.price',
        'products.stock',
        'products.image',
        'products.type',
        'products.is_best_seller',
        \DB::raw('SUM(order_items.quantity) as total_ordered')
    ])
    ->join('order_items', 'products.product_id', '=', 'order_items.product_id')
    ->groupBy(
        'products.product_id',
        'products.name',
        'products.description',
        'products.price',
        'products.stock',
        'products.image',
        'products.type',
        'products.is_best_seller'
    )
    ->orderBy('total_ordered', 'desc')
    ->take(6)
    ->get();

    $bestSellerMarked = Product::where('is_best_seller', 1)
        ->whereNotIn('product_id', $mostOrdered->pluck('product_id'))
        ->take(6 - $mostOrdered->count())
        ->get();

    $bestSellers = $mostOrdered->concat($bestSellerMarked);

    return view('home', compact('products', 'bestSellers'));
})->name('home');

Route::get('/perfumes', [PerfumesController::class, 'index']);
Route::get('/bestseller', [BestsellerController::class, 'index'])->name('bestseller');
Route::view('/aboutus', 'aboutus')->name('aboutus');
Route::view('/contact', 'contact')->name('contact');

Route::prefix('perfumes')->group(function() {
    Route::get('captivating', [PerfumesController::class, 'captivating']);
    Route::get('intense', [PerfumesController::class, 'intense']);
});

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/view.product/{id}', [ProductController::class, 'show'])->name('product.view');

// Cart Routes
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Checkout
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])->name('checkout.createPaymentIntent');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Contact
Route::post('/send-message', [ContactController::class, 'send']);
Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

// Stripe Payment
Route::get('/redirect-to-payment', [PaymentController::class, 'redirectToStripe'])->name('payment.redirect');
Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.details');
});


// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Products
    Route::get('/admin/products', [AdminController::class, 'showProducts'])->name('admin.products');
    Route::post('/admin/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
    Route::put('/admin/products/{id}', [AdminController::class, 'updateProduct'])->name('admin.products.update');
    Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct'])->name('admin.products.delete');

    // Users
    Route::get('/admin/users', [AdminController::class, 'showUsers'])->name('admin.users');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Orders
    Route::get('/admin/orders', [AdminController::class, 'showOrders'])->name('admin.orders');
    Route::post('/admin/orders/{id}/process', [AdminController::class, 'processOrder'])->name('admin.orders.process');

    // Admin Profile
    Route::get('/admin/profile', [AdminController::class, 'showProfile'])->name('admin.profile.show');
    Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('admin.profile.edit');
    Route::put('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
});

// Auth Routes
Route::get('login', [AuthenticatedSessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('guest');
Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth')->name('logout');
