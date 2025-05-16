<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SearchHistory;
use Illuminate\Support\Facades\Auth;

class TrackSearchHistory
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only track for authenticated users
        if (Auth::check() && $request->isMethod('get')) {
            // Check if this is a search request
            if ($request->routeIs('books.search') && $request->filled('query')) {
                SearchHistory::create([
                    'user_id' => Auth::id(),
                    'query' => $request->input('query'),
                    'searched_at' => now()
                ]);
            }
        }

        return $response;
    }
}