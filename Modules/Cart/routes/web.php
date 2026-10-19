<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\CartController;

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::resource('carts', CartController::class)->names('cart');
//});
Route::post('/addToCart/{book}',[CartController::class,'addToCart'])->name('add.to.cart');
Route::delete('/cart/remove/{book}',[CartController::class,'removeFromCart'])->name('remove.book');
Route::get('/cart',[CartController::class,'cart'])->name('cart');
Route::get('/cart/flush',[CartController::class,'flushCart'])->name('cart.flush');
Route::patch('/cart/change/quantity/{cartId}',[CartController::class,'changeQuantity'])->name('cart.quantity.change');
