<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\AuthorController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\PublisherController;

use App\Http\Controllers\BookCustomerController;

use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerCollectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Usuarios
    Route::resource('usuarios', UserController::class, [
        'parameters' => [
            'usuarios' => 'user',
        ],
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


/*
|--------------------------------------------------------------------------
| Customer Collection (book_customer)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::post(
        '/books/{book}/collection',
        [BookCustomerController::class, 'store']
    )->name('books.collection.store');

    Route::delete(
        '/books/{book}/collection',
        [BookCustomerController::class, 'destroy']
    )->name('books.collection.destroy');
});


Route::get('/', fn() => view('public.home'))->name('home');

// Auth customers
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.store');

Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.store');

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Área privada customer
Route::middleware('auth:customer')->group(function () {
    Route::get('/my/books', [CustomerCollectionController::class, 'index'])->name('customer.collection');
});


Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');


Route::get('/', fn() => view('public.home'))->name('home');

// Auth customers
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.store');

Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.store');

Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Área privada customer
Route::middleware('auth:customer')->group(function () {
    Route::get('/my/books', [CustomerCollectionController::class, 'index'])->name('customer.collection');
});
