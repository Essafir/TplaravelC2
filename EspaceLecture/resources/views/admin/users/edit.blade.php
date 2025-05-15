@extends('layouts.admin')

@section('title', 'Modifier un utilisateur')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Modifier l'utilisateur : {{ $user->name }}</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" 
                           value="{{ old('name', $user->name) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ old('email', $user->email) }}" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Rôle</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="change_password" name="change_password">
                        <label class="form-check-label" for="change_password">
                            Modifier le mot de passe
                        </label>
                    </div>
                </div>
                
                <div id="password_fields" style="display: none;">
                    <div class="form-group">
                        <label for="password">Nouveau mot de passe</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Afficher/masquer les champs mot de passe
    document.getElementById('change_password').addEventListener('change', function() {
        document.getElementById('password_fields').style.display = this.checked ? 'block' : 'none';
    });
</script>
@endsection