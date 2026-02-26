<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.admin.php';
require __DIR__ . '/auth.customer.php';



use App\Http\Controllers\Admin\{
    AdminAuthController,
    BookController,
    AuthorController,
    GenreController,
    CustomerController,
    PublisherController
};
use App\Http\Controllers\{
    BookCustomerController,
    CustomerAuthController,
    CustomerCollectionController,
    Controller
};

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->name('customer.')->group(function () {

    /*
|--------------------------------------------------------------------------
| Customer Protected Routes
|--------------------------------------------------------------------------
*/
    Route::middleware('auth:customer')->group(function () {

        // Customer collection
        Route::get('/collection', [CustomerCollectionController::class, 'index'])->name('collection');

        // Book collection management
        Route::post('/collection/{book}', [BookCustomerController::class, 'store'])
            ->name('collection.store');

        Route::delete('/collection/{book}', [BookCustomerController::class, 'destroy'])
            ->name('collection.destroy');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');


    /*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

    Route::middleware('auth:web')->group(function () {
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
});


/*
use App\Http\Controllers\ProfileController;


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
*/