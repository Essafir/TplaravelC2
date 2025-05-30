<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController1;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Userbookcontrole;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\Admin\SearchHistoryController;



// Public routes
Route::get('/', [BookController1::class, 'welcome'])->name('welcome');
Route::get('/books', [BookController1::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController1::class, 'show'])->name('books.show');
Route::get('/search', [BookController1::class, 'search'])->name('books.search');
Route::get('/advanced-search', [BookController1::class, 'advancedSearch'])->name('books.advanced-search');
Route::get('/about', function () {
    return view('pages.about');
})->name('about');


// User routes client
Route::prefix('user')->name('user.')->middleware(['auth', CheckRole::class . ':user','track.search'])->group(function () {
    Route::get('/', [Userbookcontrole::class, 'welcome'])->name('welcomeuser');
    Route::get('books', [Userbookcontrole::class, 'index'])->name('index');
    Route::get('books/{book}', [Userbookcontrole::class, 'show'])->name('show');
    Route::get('search', [Userbookcontrole::class, 'search'])->name('searchuser');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('search-history/{history}', [ProfileController::class, 'deleteSearchHistory'])->name('search-history.destroy');
    Route::get('advanced-search', [Userbookcontrole::class, 'advancedSearch'])->name('advanced-search');
    Route::post('books/{book}/reviews', [ReviewController::class, 'store'])->name('books.reviews.store');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin routes 
Route::prefix('admin')->name('admin.')->middleware(['auth', CheckRole::class . ':admin'])->group(function () {
   
    Route::get('/', [BookController::class, 'dashboard'])->name('dashboard');

    
    Route::resource('books', BookController::class)->names([
        'index' => 'books.index',
        'create' => 'books.create',
        'store' => 'books.store',
        'show' => 'books.show',
        'edit' => 'books.edit',
        'update' => 'books.update',
        'destroy' => 'books.destroy'
    ]);

    
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create'); 
        Route::post('/', [UserController::class, 'store'])->name('store'); 
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); 
        Route::post('/{user}/ban', [UserController::class, 'ban'])->name('ban');
        Route::post('/{user}/unban', [UserController::class, 'unban'])->name('unban');

        Route::get('/{user}/history', [SearchHistoryController::class, 'userHistory'])->name('history');
    });

    
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy'
    ]);


    
    Route::prefix('search-history')->name('search-history.')->group(function () {
        Route::get('/', [SearchHistoryController::class, 'index'])->name('index');
        Route::get('/user/{user}', [SearchHistoryController::class, 'userHistory'])->name('user');
        Route::delete('/{history}', [SearchHistoryController::class, 'destroy'])->name('destroy');
        Route::delete('/', [SearchHistoryController::class, 'clearAll'])->name('clear-all');
    });


    ;
});

// Auth routes
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});