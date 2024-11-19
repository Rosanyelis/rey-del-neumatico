<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/get-stock/{product}', [App\Http\Controllers\Api\V1\ProductController::class, 'getStock'])->name('get-stock');
Route::get('/update-stock', [App\Http\Controllers\Api\V1\ProductController::class, 'updateStock'])->name('update-stock');
Route::get('/get-sku/{codeProduct}', [App\Http\Controllers\Api\V1\ProductController::class, 'SearchProductByCode'])->name('get-sku');
Route::get('/update-stock-wp/{sku}/{quantity}', [App\Http\Controllers\Api\V1\ProductController::class, 'updateStockWp'])->name('update-stock-wp');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
