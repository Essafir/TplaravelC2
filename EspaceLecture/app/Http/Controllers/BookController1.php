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
                ->withAvg('reviews', 'rating')
                ->orderBy('published_at', 'desc')
                ->take(8)
                ->get();

            $popularBooks = Book::with('category')
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_count', 'desc')
                ->take(8)
                ->get();

            return view('welcome', compact('recentBooks', 'popularBooks'));
        }

    public function index(Request $request)
{
    $query = Book::query()->with('category', 'reviews')
        ->withAvg('reviews', 'rating') // Add average rating for all books
        ->withCount('reviews'); // Add review count for all books

    // Apply filters
    if ($request->has('category') && $request->category) {
        $query->where('category_id', $request->category);
    }

    if ($request->has('year') && $request->year) {
        $query->whereYear('published_at', $request->year);
    }

    if ($request->has('rating') && $request->rating) {
        $query->having('reviews_avg_rating', '>=', $request->rating);
    }

    // Apply sorting
    switch ($request->sort) {
        case 'recent':
            $query->orderBy('published_at', 'desc');
            break;
        case 'popular':
            $query->orderBy('reviews_count', 'desc');
            break;
        case 'rating':
            $query->orderBy('reviews_avg_rating', 'desc');
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
    $query = Book::query()->with(['category', 'reviews']);

    // Handle search query - FIXED the InputBag issue
    if ($request->filled('query')) {
        $searchTerm = $request->input('query'); // Properly get the string value
        $query->where(function($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('author', 'like', "%{$searchTerm}%")
              ->orWhere('summary', 'like', "%{$searchTerm}%");
        });
    }

    // Category filter
    if ($request->filled('category')) {
        $query->where('category_id', $request->input('category'));
    }

    // Publication year range
    if ($request->filled('year_from')) {
        $query->whereYear('published_at', '>=', $request->input('year_from'));
    }
    if ($request->filled('year_to')) {
        $query->whereYear('published_at', '<=', $request->input('year_to'));
    }

    // Page count range
    if ($request->filled('pages_min')) {
        $query->where('pages', '>=', $request->input('pages_min'));
    }
    if ($request->filled('pages_max')) {
        $query->where('pages', '<=', $request->input('pages_max'));
    }

    // Rating filter
    if ($request->filled('rating')) {
        $query->whereHas('reviews', function($q) use ($request) {
            $q->selectRaw('avg(rating) as avg_rating, book_id')
              ->groupBy('book_id')
              ->having('avg_rating', '>=', $request->input('rating'));
        });
    }

    // Sorting
    $sortOptions = [
        'newest' => ['published_at', 'desc'],
        'oldest' => ['published_at', 'asc'],
        'title_asc' => ['title', 'asc'],
        'title_desc' => ['title', 'desc'],
        'popular' => ['reviews_count', 'desc']
    ];

    $sort = $request->input('sort', 'newest');
    if (array_key_exists($sort, $sortOptions)) {
        [$column, $direction] = $sortOptions[$sort];
        if ($column === 'reviews_count') {
            $query->withCount('reviews')->orderBy($column, $direction);
        } else {
            $query->orderBy($column, $direction);
        }
    }

    // Get results with pagination
    $books = $query->paginate(12)->appends($request->query());
    $categories = Category::all();

    return view('books.search-results', [
        'books' => $books,
        'categories' => $categories,
        'searchParams' => $request->all()
    ]);
}

    public function advancedSearch()
    {
        $categories = Category::all();
        return view('books.advanced-search', compact('categories'));
    }
}