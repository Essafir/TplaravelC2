<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Book $book)
{
    $request->validate([
        'rating' => 'required|integer|between:1,5',
        'comment' => 'nullable|string|max:1000',
    ]);

    // verifier que luser na pa donner un revie pour ce book
    $existingReview = Review::where('user_id', Auth::id())
        ->where('book_id', $book->id)
        ->first();

    if ($existingReview) {
        return back()->with('error', 'Vous avez déjà donné votre avis sur ce livre.');
    }

    Review::create([
        'user_id' => Auth::id(),
        'book_id' => $book->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
    ]);

    return back()->with('success', 'Votre avis a été enregistré avec succès.');
} 

    

    
    
}
