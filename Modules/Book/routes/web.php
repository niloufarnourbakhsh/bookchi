<?php

use Illuminate\Support\Facades\Route;
use Modules\Book\Http\Controllers\BookController;
use Modules\Book\Http\Controllers\CatalogController;
use Modules\Book\Http\Controllers\ContributorController;
use Modules\Book\Http\Controllers\ManageBookPhotosController;
use Modules\Book\Http\Controllers\PublisherController;
use Modules\Book\Http\Controllers\CategoryController;

//Route::middleware(['auth', 'verified'])->group(function () {
//    Route::resource('books', BookController::class)->names('book');
//});
Route::resource('books', BookController::class)->names('book')->except('show');
Route::resource('publishers', PublisherController::class)->names('publisher')->except('show');
Route::resource('contributors', ContributorController::class)->names('contributors')->except('show');
Route::resource('categories', CategoryController::class)->names('categories')->except('show');

Route::get('/book-gallery/{book}',[ManageBookPhotosController::class,'index'])->name('book.gallery');
Route::post('/book-gallery/{book}',[ManageBookPhotosController::class,'store'])->name('book.gallery.store');
Route::delete('/book-gallery/{book}/{file}',[ManageBookPhotosController::class,'destroy'])->name('book.gallery.delete');
Route::get('/catalog',[CatalogController::class,'index'])->name('catalog');
Route::get('/catalog/{book}',[CatalogController::class,'show'])->name('catalog.show');
