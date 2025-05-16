<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController1;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Userbookcontrole;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
// Uncomment and create these controllers if you want to use them
// use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\CheckRole;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within the "web" middleware group.
|
*/

// Public routes (accessible without authentication)
Route::get('/', [BookController1::class, 'welcome'])->name('welcome');

// Authenticated user routes
Route::prefix('user')->name('user.')->middleware(['auth', CheckRole::class . ':user'])->group(function () {
    Route::get('books', [Userbookcontrole::class, 'index'])->name('index'); // This will be 'user.books' as name and '/user/books' as path
    Route::get('books/{book}', [Userbookcontrole::class, 'show'])->name('show');
    Route::get('search', [Userbookcontrole::class, 'search'])->name('search');
    Route::get('profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('advanced-search', [Userbookcontrole::class, 'advancedSearch'])->name('advanced-search');
});
// Public route
Route::get('/', [BookController1::class, 'welcome'])->name('welcome');
Route::get('/books', [BookController1::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController1::class, 'show'])->name('books.show');
Route::get('/search', [BookController1::class, 'search'])->name('books.search');
Route::get('/advanced-search', [BookController1::class, 'advancedSearch'])->name('books.advanced-search');
Route::get('/about', function () {
    return view('pages.about');
})->name('about');
// Authentication routes (Breeze)
require __DIR__.'/auth.php';

// Admin routes (only accessible by authenticated users with 'admin' role)
Route::prefix('admin')->name('admin.')->middleware(['auth', CheckRole::class . ':admin'])->group(function () {
    // Dashboard
    Route::get('/', [BookController::class, 'dashboard'])->name('dashboard');

    // Books resource (CRUD)
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
        Route::get('/create', [UserController::class, 'create'])->name('create'); // Ajouté pour cohérence
        Route::post('/', [UserController::class, 'store'])->name('store'); // Ajouté pour cohérence
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy'); // Ajouté pour cohérence
        Route::post('/{user}/ban', [UserController::class, 'ban'])->name('ban');
        Route::post('/{user}/unban', [UserController::class, 'unban'])->name('unban');
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
    // Routes pour la gestion des utilisateurs
  
});

// Common authenticated routes (for all logged-in users)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes (uncomment if you have profile functionality)
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});