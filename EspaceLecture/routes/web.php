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

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [BookController1::class, 'welcome'])->name('welcome');
Route::get('/books', [BookController1::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController1::class, 'show'])->name('books.show');
Route::get('/search', [BookController1::class, 'search'])->name('books.search');
Route::get('/advanced-search', [BookController1::class, 'advancedSearch'])->name('books.advanced-search');
Route::get('/about', function () {
    return view('pages.about');
})->name('about');


// User routes (utilisateurs avec rôle 'user')
Route::prefix('user')->name('user.')->middleware(['auth', CheckRole::class . ':user'])->group(function () {
    Route::get('books', [Userbookcontrole::class, 'index'])->name('index');
    Route::get('books/{book}', [Userbookcontrole::class, 'show'])->name('show');
    Route::get('search', [Userbookcontrole::class, 'search'])->name('search');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('advanced-search', [Userbookcontrole::class, 'advancedSearch'])->name('advanced-search');
    Route::post('books/{book}/reviews', [ReviewController::class, 'store'])->name('books.reviews.store');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin routes (utilisateurs avec rôle 'admin')
Route::prefix('admin')->name('admin.')->middleware(['auth', CheckRole::class . ':admin'])->group(function () {
    // Dashboard
    Route::get('/', [BookController::class, 'dashboard'])->name('dashboard');

    // Books CRUD
    Route::resource('books', BookController::class)->names([
        'index' => 'books.index',
        'create' => 'books.create',
        'store' => 'books.store',
        'show' => 'books.show',
        'edit' => 'books.edit',
        'update' => 'books.update',
        'destroy' => 'books.destroy'
    ]);

    // Users management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create'); 
        Route::post('/', [UserController::class, 'store'])->name('store'); 
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); 
        Route::post('/{user}/ban', [UserController::class, 'ban'])->name('ban');
        Route::post('/{user}/unban', [UserController::class, 'unban'])->name('unban');
    });

    // Categories management
    Route::resource('categories', CategoryController::class)->names([
        'index' => 'categories.index',
        'create' => 'categories.create',
        'store' => 'categories.store',
        'show' => 'categories.show',
        'edit' => 'categories.edit',
        'update' => 'categories.update',
        'destroy' => 'categories.destroy'
    ]);

    // Reviews management (optionnel - si vous voulez que l'admin puisse gérer les avis)
    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('reviews/{review}', [ReviewController::class, 'adminDestroy'])->name('reviews.adminDestroy');
});

// Authentication routes
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});