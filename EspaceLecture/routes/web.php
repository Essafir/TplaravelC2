<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Pages publiques
Route::get('/', function () {
    return redirect()->route('books.index');
});

// Authentification (Breeze)
require __DIR__.'/auth.php';

// User routes (protected by auth and user role)
// Route::middleware(['auth', 'role:user'])->group(function () {
//     // Main user dashboard
//     Route::get('/dashboard', [UserController::class, 'dashboard'])
//         ->name('dashboard');
    
//     // User profile routes
//     Route::prefix('profile')->group(function () {
//         Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
//         Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
//         Route::patch('/update', [ProfileController::class, 'update'])->name('profile.update');
//     });
    
//     // Book-related routes
//     Route::resource('books', BookController::class)->only(['index', 'show']);
    
//     // Review routes
//     Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])
//         ->name('reviews.store');
// });

// Admin routes (protected by auth and admin role)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Admin dashboard
    Route::get('/dashboard', [BookController::class, 'dashboard'])
        ->name('admin.dashboard');
    
    // Books management
    Route::resource('books', BookController::class)->except(['show']);
    
    // Categories management
    Route::resource('categories', CategoryController::class)->except(['show']);
    
});

// Public routes (accessible to all)
Route::middleware('guest')->group(function () {
    // Authentication routes (from Breeze)
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    // ... other auth routes
});

// Common authenticated routes (accessible to all logged in users)
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});