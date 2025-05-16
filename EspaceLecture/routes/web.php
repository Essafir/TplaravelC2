<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController1;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;

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
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    
    // Routes pour bannir/débannir
        Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');

});

// Common authenticated routes (for all logged-in users)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes (uncomment if you have profile functionality)
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});