<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfumesController;
use App\Models\Product;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $bestSellers = Product::all(); // Fetch the best sellers (or however you define them)
    return view('welcome', compact('bestSellers'));
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/perfumes', [PerfumesController::class, 'index']);

