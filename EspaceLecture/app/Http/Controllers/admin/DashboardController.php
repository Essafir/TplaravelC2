<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'books_count' => Book::count(),
            'users_count' => User::count(),
            'categories_count' => Category::count(),
            'recent_books' => Book::latest()->take(5)->get(),
            'top_books' => Book::withCount('reviews')
                            ->orderBy('reviews_count', 'desc')
                            ->take(5)
                            ->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}