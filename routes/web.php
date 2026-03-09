<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.admin.php';
require __DIR__ . '/auth.customer.php';

use App\Http\Controllers\Admin\{
    BookController,
    AuthorController,
    GenreController,
    CustomerController,
    PublisherController
};
//use App\Http\Controllers\Admin\mongoDB\AuthorController;
use App\Http\Controllers\{
    BookCustomerController,
    CustomerCollectionController
};

// HOME PAGE

Route::get('/', fn() => view('public.home'));


/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->name('customer.')->middleware('auth:customer')->group(function () {

    // Customer collection
    Route::get('/collection', [CustomerCollectionController::class, 'index'])->name('collection');

    // Authors view
    Route::get('/authors/', [AuthorController::class, 'show'])->name('author');
    Route::get('/authors/{author}', [AuthorController::class, 'show'])->name('author');


    // Book collection management
    Route::post('/collection/{book}', [BookCustomerController::class, 'store'])->name('collection.store');

    Route::delete('/collection/{book}', [BookCustomerController::class, 'destroy'])->name('collection.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth:web', 'verified'])->group(function () {

    // Users
    Route::resource('customers', CustomerController::class);

    // Books
    Route::resource('books', BookController::class);

    // Authors
    Route::resource('authors', AuthorController::class);

    // Genres
    Route::resource('genres', GenreController::class);

    // Publishers
    Route::resource('publishers', PublisherController::class);
});
