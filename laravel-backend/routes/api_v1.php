<?php

use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/categories/{category}', function ($category) {
    return response()->json([
        'id' => $category,
    ]);
})->name('categories.show');

Route::get('/products', [ProductController::class, 'index'])
    ->name('products.index');

Route::get('/products/queries', [ProductController::class, 'queries'])
    ->name('products.queries');

Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('products.show');

    Route::post('/products', [ProductController::class, 'store'])
    ->name('products.store');

Route::put('/products/{product}', [ProductController::class, 'update'])
    ->name('products.update');

Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->name('products.destroy');