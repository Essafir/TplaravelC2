<?php

namespace App\Http\Controllers;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\SearchHistory;

class ProfileController extends Controller
{
    
     public function show()
    {
        $user = auth()->user();
        
        $reviews = Review::with('book')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'reviews_page');
            
        $searchHistory = SearchHistory::where('user_id', $user->id)
            ->orderBy('searched_at', 'desc')
            ->paginate(10, ['*'], 'history_page');

        return view('user.profile', [
            'user' => $user,
            'reviews' => $reviews,
            'searchHistory' => $searchHistory
        ]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

   public function update(Request $request)
{
    $user = auth()->user();

    // ✅ Validation
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id, // exclude current user
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // ✅ Update fields
    $user->name = $request->name;
    $user->email = $request->email;

    // ✅ Avatar upload (same as before)
    if ($request->hasFile('avatar')) {
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    $user->save();

    return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
}
public function deleteSearchHistory(SearchHistory $history)
{
    // Verify the search history belongs to the authenticated user
    if ($history->user_id !== auth()->id()) {
        abort(403);
    }

    $history->delete();

    return back()->with('success', 'Entrée supprimée de votre historique.');
}

    
}