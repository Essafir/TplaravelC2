@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">Filtres</div>
                <div class="card-body">
                    <form action="{{ route('books.index') }}" method="GET">
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

                        <!-- Publication Year Filter -->
                        <div class="mb-3">
                            <label for="year" class="form-label">Année de publication</label>
                            <select name="year" id="year" class="form-select">
                                <option value="">Toutes les années</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="rating" class="form-label">Note minimale</label>
                            <select name="rating" id="rating" class="form-select">
                                <option value="">Toutes les notes</option>
                                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 étoiles et plus</option>
                                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 étoiles et plus</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Appliquer les filtres</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Books List -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Liste des livres</h1>
                <div>
                    <span class="me-2">Trier par:</span>
                    <div class="btn-group">
                        <a href="{{ route('books.index', array_merge(request()->query(), ['sort' => 'recent'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'recent' ? 'active' : '' }}">
                            Plus récents
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'popular' ? 'active' : '' }}">
                            Plus populaires
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->query(), ['sort' => 'rating'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'rating' ? 'active' : '' }}">
                            Meilleures notes
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($books as $book)
                    <div class="col-md-4 mb-4">
                        @include('partials.book-card', ['book' => $book])
                    </div>
                @endforeach
            </div>

            
            <div class="d-flex justify-content-center">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@endsection