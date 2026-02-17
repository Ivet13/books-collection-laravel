<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    UserController,
    BookController,
    AuthorController,
    GenreController,
    PublisherController
};
use App\Http\Controllers\{
    BookCustomerController,
    CustomerAuthController,
    CustomerCollectionController
};

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('public.home'))->name('home');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('')->name('customer.')->group(function () {
    // Registration
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.store');

    // Login
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.store');

    // Logout
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Customer Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:customer')->prefix('')->name('')->group(function () {
    // Customer collection/mi-coleccion
    Route::get('/mi-coleccion', [CustomerCollectionController::class, 'index'])->name('customer.collection');

    // Book collection management
    Route::post('/books/{book}/collection', [BookCustomerController::class, 'store'])
        ->name('books.collection.store');
    Route::delete('/books/{book}/collection', [BookCustomerController::class, 'destroy'])
        ->name('books.collection.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Users
    Route::resource('usuarios', UserController::class, [
        'parameters' => ['usuarios' => 'user'],
        'names' => [
            'index' => 'users',
            'create' => 'users_create',
            'edit' => 'users_edit',
            'store' => 'users_store',
            'destroy' => 'users_destroy',
        ]
    ]);

    // Books
    Route::resource('books', BookController::class);

    // Authors
    Route::resource('authors', AuthorController::class);

    // Genres
    Route::resource('genres', GenreController::class);

    // Publishers
    Route::resource('publishers', PublisherController::class);
});
