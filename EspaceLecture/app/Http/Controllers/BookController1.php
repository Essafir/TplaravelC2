<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController1 extends Controller
{
    public function welcome()
    {
        $recentBooks = Book::with('category')
            ->orderBy('published_at', 'desc')
            ->take(8)
            ->get();

        $popularBooks = Book::with('category')
            ->withCount('reviews')
            ->orderBy('reviews_count', 'desc')
            ->take(8)
            ->get();

        return view('welcome', compact('recentBooks', 'popularBooks'));
    }

    public function index(Request $request)
    {
        $query = Book::query()->with('category', 'reviews');

        // Apply filters
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('year') && $request->year) {
            $query->whereYear('published_at', $request->year);
        }

        if ($request->has('rating') && $request->rating) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->selectRaw('avg(rating) as avg_rating, book_id')
                  ->groupBy('book_id')
                  ->having('avg_rating', '>=', $request->rating);
            });
        }

        // Apply sorting
        switch ($request->sort) {
            case 'recent':
                $query->orderBy('published_at', 'desc');
                break;
            case 'popular':
                $query->withCount('reviews')->orderBy('reviews_count', 'desc');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            default:
                $query->orderBy('title');
        }

        $books = $query->paginate(12);
        $categories = Category::all();
        $years = Book::selectRaw('YEAR(published_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('books.index', compact('books', 'categories', 'years'));
    }

    public function show(Book $book)
    {
        $book->load('category', 'reviews.user');
        
        $userReview = null;
        if (auth()->check()) {
            $userReview = $book->reviews()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('books.show', compact('book', 'userReview'));
    }

    public function search(Request $request)
    {
        $query = Book::query()->with('category');

        if ($request->has('query') && $request->query) {
            $searchTerm = $request->query;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('author', 'like', "%{$searchTerm}%")
                  ->orWhere('summary', 'like', "%{$searchTerm}%");
            });
        }

        // Apply other filters from advanced search
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('year_from') && $request->year_from) {
            $query->whereYear('published_at', '>=', $request->year_from);
        }

        if ($request->has('year_to') && $request->year_to) {
            $query->whereYear('published_at', '<=', $request->year_to);
        }

        if ($request->has('pages_min') && $request->pages_min) {
            $query->where('pages', '>=', $request->pages_min);
        }

        if ($request->has('pages_max') && $request->pages_max) {
            $query->where('pages', '<=', $request->pages_max);
        }

        if ($request->has('rating') && $request->rating) {
            $query->whereHas('reviews', function($q) use ($request) {
                $q->selectRaw('avg(rating) as avg_rating, book_id')
                  ->groupBy('book_id')
                  ->having('avg_rating', '>=', $request->rating);
            });
        }

        $books = $query->paginate(12);
        $categories = Category::all();

        return view('books.search-results', compact('books', 'categories'));
    }

    public function advancedSearch()
    {
        $categories = Category::all();
        return view('books.advanced-search', compact('categories'));
    }
}