<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,user',
        ];

        if ($request->change_password) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $data = $request->validate($rules);

        if ($request->change_password) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function ban(User $user)
    {
        $user->update(['banned_at' => now()]);
        return back()->with('success', 'Utilisateur banni');
    }

    public function unban(User $user)
    {
        $user->update(['banned_at' => null]);
        return back()->with('success', 'Utilisateur débanni');
    }
}