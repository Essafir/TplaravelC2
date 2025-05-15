<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Vérifier si l'utilisateur a déjà commenté ce livre
        $existingReview = Review::where('user_id', auth()->id())
                                ->where('book_id', $book->id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Vous avez déjà commenté ce livre.');
        }

        Review::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Votre avis a été enregistré.');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();
        return back()->with('success', 'Avis supprimé avec succès.');
    }
}