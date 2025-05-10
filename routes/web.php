<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfumesController;
use App\Http\Controllers\BestsellerController;
use App\Models\Product;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $bestSellers = Product::all(); // Fetch the best sellers (or however you define them)
    return view('welcome', compact('bestSellers'));
})->name('welcome');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/perfumes', [PerfumesController::class, 'index']);

Route::view('/aboutus', 'aboutus')->name('aboutus');
Route::view('/contact', 'contact')->name('contact');
Route::view('/home', 'home')->name('welcome');


Route::get('/home', function () {
    $products = Product::all();
    return view('home', compact('products'));
})->name('home');