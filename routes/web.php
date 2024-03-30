<?php

use App\Http\Controllers\productController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/products', [productController::class, 'index'])->name('product.index');
Route::get('/products/create', [productController::class, 'create'])->name('product.create');
Route::get('/products/{product}/edit', [productController::class, 'edit'])->name('product.edit');
Route::post('/products', [productController::class, 'store'])->name('product.store');
Route::put('/products/{product}', [productController::class, 'update'])->name('product.updates');
Route::delete('/products/{product}', [productController::class, 'destroy'])->name('product.destroy');