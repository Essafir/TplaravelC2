<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

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

// Livres
Route::resource('books', BookController::class)->only(['index', 'show']);

// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    // Profil utilisateur
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
    });
    
    // Commentaires
    Route::post('/books/{book}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Routes admin
    Route::middleware(['admin'])->group(function () {
        // Gestion des livres (CRUD complet)
        Route::resource('books', BookController::class)->except(['index', 'show']);
        
        // Gestion des catégories
        Route::resource('categories', CategoryController::class);
        
        // Tableau de bord admin
        Route::prefix('admin')->group(function () {
            Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
            Route::put('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.update-role');
        });
        
    });
});

Route::get('/test', function () {
    return view('test');
});
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');
