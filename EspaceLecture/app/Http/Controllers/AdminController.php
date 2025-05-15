<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // Tableau de bord admin
    public function dashboard()
    {
        $stats = [
            'totalBooks' => Book::count(),
            'totalUsers' => User::count(),
            'totalCategories' => Category::count(),
            'totalReviews' => Review::count(),
            'recentBooks' => Book::latest()->take(5)->get(),
            'topRatedBooks' => Book::withAvg('reviews', 'rating')
                                 ->withCount('reviews')
                                 ->orderBy('reviews_avg_rating', 'desc')
                                 ->take(5)
                                 ->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Gestion des utilisateurs
    public function users()
    {
        $users = User::paginate(10);
        return view('admin.users', compact('users'));
    }

    // Mise à jour du rôle utilisateur
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        // Empêcher un admin de se rétrograder lui-même
        if ($user->id === auth()->id() && $request->role === 'user') {
            return back()->with('error', 'Vous ne pouvez pas modifier votre propre rôle.');
        }

        $user->update(['role' => $request->role]);

        return back()->with('success', 'Rôle utilisateur mis à jour.');
    }

    // Statistiques avancées (optionnel)
    public function statistics()
    {
        $booksPerCategory = Category::withCount('books')->get();
        $reviewsPerMonth = Review::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                               ->groupBy('month')
                               ->get();

        return view('admin.statistics', compact('booksPerCategory', 'reviewsPerMonth'));
    }
}