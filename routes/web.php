<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

/**/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';


//use Illuminate\Support\Facades\Route;
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
| Customer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->name('customer.')->group(function () {

    /*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/

    // Registration
    Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.store');

    // Login
    Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.store');

    // Logout
    Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

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
    /*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
    // Login
    Route::get('/', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.store');

    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');


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
