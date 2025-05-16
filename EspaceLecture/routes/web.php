<?php

use Illuminate\Support\Facades\Route;
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
Route::get('/', function () {
    return view('welcome');
});

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
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    
    // Routes pour bannir/débannir
        Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('admin.users.ban');
        Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('admin.users.unban');
    // Categories (uncomment if you have a CategoryController)
    // Route::resource('categories', CategoryController::class)->names([
    //     'index' => 'categories.index',
    //     'create' => 'categories.create',
    //     'store' => 'categories.store',
    //     'show' => 'categories.show',
    //     'edit' => 'categories.edit',
    //     'update' => 'categories.update',
    //     'destroy' => 'categories.destroy'
    // ]);

});

// Common authenticated routes (for all logged-in users)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes (uncomment if you have profile functionality)
    // Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
});