<?php

use Illuminate\Support\Facades\Route;
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
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('public.home'))->name('home');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->name('customer.')->group(function () {
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

Route::middleware('auth:customer')->prefix('customer')->name('customer.')->group(function () {
    // Customer collection
    Route::get('/collection', [CustomerCollectionController::class, 'index'])->name('collection');

    // Book collection management
    Route::post('/collection/{collectionId}/store', [BookCustomerController::class, 'store'])
        ->name('collection.store');
    Route::delete('/collection/{collectionId}/destroy', [BookCustomerController::class, 'destroy'])
        ->name('collection.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Login
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Users
    Route::resource('usuarios', AdminAuthController::class, [
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
