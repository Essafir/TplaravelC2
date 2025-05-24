<?php

namespace App\Http\Controllers\Admin;

use App\Models\SearchHistory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SearchHistoryController extends Controller
{
    /**
     * Affiche tout l'historique
     */
    public function index()
    {
        $histories = SearchHistory::with('user')
                      ->orderBy('searched_at', 'desc')
                      ->paginate(20);
        
        return view('admin.search-history.index', compact('histories'));
    }

    /**
     * Affiche l'historique d'un utilisateur spécifique
     */
    public function userHistory($userId)
    {
        $userHistories = SearchHistory::where('user_id', $userId)
                           ->orderBy('searched_at', 'desc')
                           ->paginate(15);
        
        return view('admin.search-history.user', compact('userHistories'));
    }

    /**
     * Supprime une entrée spécifique
     */
    public function destroy($id)
    {
        SearchHistory::findOrFail($id)->delete();
        
        return back()->with('success', 'Entrée supprimée avec succès');
    }

    /**
     * Vide tout l'historique
     */
    public function clearAll()
    {
        SearchHistory::truncate();
        
        return back()->with('success', 'Historique vidé avec succès');
    }
}