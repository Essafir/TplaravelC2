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

                        <!-- Rating Filter -->
                        <div class="mb-3">
                            <label for="rating" class="form-label">Note minimale</label>
                            <select name="rating" id="rating" class="form-select">
                                <option value="">Toutes les notes</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                        {{ $i }} étoile{{ $i > 1 ? 's' : '' }} et plus
                                    </option>
                                @endfor
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
                        <div class="card h-100">
                            <img src="{{ $book->cover_url }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $book->title }}</h5>
                                <p class="card-text text-muted small">{{ $book->author }} ({{ $book->published_year }})</p>
                                
                                <!-- Affichage de la note moyenne -->
                                <div class="mb-2">
                                    <div class="star-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $book->average_rating)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == ceil($book->average_rating) && $book->average_rating - floor($book->average_rating) > 0)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-1">({{ $book->reviews_count }} avis)</span>
                                    </div>
                                </div>
                                
                                <p class="card-text">{{ Str::limit($book->description, 100) }}</p>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between">
                                <a href="{{ route('books.show', $book) }}" class="btn btn-primary btn-sm">
                                    Détails
                                </a>
                                @auth
                                    @if(!$book->has_user_review)
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $book->id }}">
                                            Noter
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Modal pour les avis -->
                    @auth
                        @if(!$book->has_user_review)
                            <div class="modal fade" id="reviewModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Donnez votre avis</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('books.reviews.store', $book) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Note</label>
                                                    <div class="rating">
                                                        @for($i = 5; $i >= 1; $i--)
                                                            <input type="radio" id="star{{ $i }}-{{ $book->id }}" name="rating" value="{{ $i }}" required>
                                                            <label for="star{{ $i }}-{{ $book->id }}"><i class="fas fa-star"></i></label>
                                                        @endfor
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="comment-{{ $book->id }}" class="form-label">Commentaire (optionnel, max 500 caractères)</label>
                                                    <textarea class="form-control" id="comment-{{ $book->id }}" name="comment" rows="3" maxlength="500"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Envoyer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endauth
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating input {
        display: none;
    }
    .rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        padding: 0 2px;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating label:hover ~ label {
        color: #ffc107;
    }
    .star-rating {
        font-size: 1rem;
    }
</style>
@endpush

@endsection