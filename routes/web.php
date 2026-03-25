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

use App\Http\Controllers\Public\LanguageController;
use App\Http\Controllers\ImageController;

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

Route::get('/', function () {})->middleware('setlocale');

Route::post('/change-language', [LanguageController::class, 'changeLanguage'])->name('change-language');

Route::group(['middleware' => 'sitemap'], function () {
    Route::get('/es', 'App\Http\Controllers\Public\HomeController@index')->name('es.home');
    Route::get('/es/autores/{name}', 'App\Http\Controllers\Public\AuthorController@show')->name('es.author');

    Route::get('/en', 'App\Http\Controllers\Public\HomeController@index')->name('en.home');
    Route::get('/en/authors/{name}', 'App\Http\Controllers\Public\AuthorController@show')->name('en.author');
});

Route::post('/images', 'App\Http\Controllers\Admin\ImageController@store')->name('images_store');
Route::get('/images/thumb/{filename}', 'App\Http\Controllers\Admin\ImageController@showThumb')->name('images_thumb');
Route::get('/images', 'App\Http\Controllers\Admin\ImageController@index')->name('images_index');
Route::delete('/images/{filename}', 'App\Http\Controllers\Admin\ImageController@destroy')->name('images_destroy');
Route::put('/images/modify/{id}', 'App\Http\Controllers\Admin\ImageController@modify')->name('images_modify');

Route::get('/images/{entity}/{entityId}/{filename}', 'App\Http\Controllers\Front\ImageController@showImage')->name('image');
