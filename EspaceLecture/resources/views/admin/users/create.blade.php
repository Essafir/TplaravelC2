{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')

@section('title', 'Créer un nouvel utilisateur')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Créer un nouvel utilisateur</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nom complet *</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="role">Rôle *</label>
                    <select class="form-control @error('role') is-invalid @enderror" 
                            id="role" name="role" required>
                        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Utilisateur</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe *</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe *</label>
                    <input type="password" class="form-control" 
                           id="password_confirmation" name="password_confirmation" required>
                </div>
                
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer l'utilisateur
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection