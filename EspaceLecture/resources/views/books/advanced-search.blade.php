@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Recherche avancée</div>

                <div class="card-body">
                    <form action="{{ route('books.search') }}" method="GET">
                        <!-- Keyword Search -->
                        <div class="mb-3">
                            <label for="query" class="form-label">Mots-clés</label>
                            <input type="text" name="query" id="query" class="form-control" 
                                   value="{{ request('query') }}" 
                                   placeholder="Titre, auteur, mot-clé...">
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select name="category" id="category" class="form-select">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Publication Year Range -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="year_from" class="form-label">Année de publication (de)</label>
                                <input type="number" name="year_from" id="year_from" class="form-control" 
                                       value="{{ request('year_from') }}" 
                                       min="1900" max="{{ date('Y') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="year_to" class="form-label">Année de publication (à)</label>
                                <input type="number" name="year_to" id="year_to" class="form-control" 
                                       value="{{ request('year_to') }}" 
                                       min="1900" max="{{ date('Y') }}">
                            </div>
                        </div>

                        <!-- Pages Range -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="pages_min" class="form-label">Nombre de pages (min)</label>
                                <input type="number" name="pages_min" id="pages_min" class="form-control" 
                                       value="{{ request('pages_min') }}" 
                                       min="1">
                            </div>
                            <div class="col-md-6">
                                <label for="pages_max" class="form-label">Nombre de pages (max)</label>
                                <input type="number" name="pages_max" id="pages_max" class="form-control" 
                                       value="{{ request('pages_max') }}" 
                                       min="1">
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div class="mb-3">
                            <label for="rating" class="form-label">Note minimale</label>
                            <select name="rating" id="rating" class="form-select">
                                <option value="">Toutes les notes</option>
                                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 étoiles</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles et plus</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles et plus</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Rechercher</button>
                            <a href="{{ route('books.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection