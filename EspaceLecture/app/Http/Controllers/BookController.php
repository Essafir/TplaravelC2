<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category', 'reviews');

        // Recherche par titre ou auteur
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                  ->orWhere('author', 'like', '%'.$request->search.'%');
            });
        }

        // Filtre par catégorie
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par statut
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Tri
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'title':
                $query->orderBy('title');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderBy('reviews_avg_rating', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $books = $query->paginate(10);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load('reviews.user');
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $this->authorize('create', Book::class);
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
            'pages' => 'required|integer|min:1',
            'published_at' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:available,borrowed',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('book_covers', 'public');
        }

        Book::create($validated);

        return redirect()->route('books.index')->with('success', 'Livre ajouté avec succès.');
    }

    public function edit(Book $book)
    {
        $this->authorize('update', $book);
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'summary' => 'nullable|string|max:2000',
            'pages' => 'required|integer|min:1',
            'published_at' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:available,borrowed',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('cover')) {
            // Supprimer l'ancienne image si elle existe
            if ($book->cover) {
                Storage::disk('public')->delete($book->cover);
            }
            $validated['cover'] = $request->file('cover')->store('book_covers', 'public');
        }

        $book->update($validated);

        return redirect()->route('books.index')->with('success', 'Livre mis à jour avec succès.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        if ($book->cover) {
            Storage::disk('public')->delete($book->cover);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livre supprimé avec succès.');
    }
}