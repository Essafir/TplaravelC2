<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà donné votre avis sur ce livre.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'book_id' => $request->book_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Votre avis a été enregistré avec succès.');
    }
    public function destroy(Review $review)
    {
        // Seul l'auteur de l'avis ou un admin peut le supprimer
        if ($review->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Avis supprimé avec succès');
    }

    // Pour l'admin
    public function index()
    {
        $reviews = Review::with(['user', 'book'])->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function adminDestroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Avis supprimé avec succès');
    }
    
}
