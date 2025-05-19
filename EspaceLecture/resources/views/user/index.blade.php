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
                        <a href="{{ route('user.index', array_merge(request()->query(), ['sort' => 'recent'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'recent' ? 'active' : '' }}">
                            Plus récents
                        </a>
                        <a href="{{ route('user.index', array_merge(request()->query(), ['sort' => 'popular'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'popular' ? 'active' : '' }}">
                            Plus populaires
                        </a>
                        <a href="{{ route('user.index', array_merge(request()->query(), ['sort' => 'rating'])) }}" class="btn btn-outline-secondary {{ request('sort') == 'rating' ? 'active' : '' }}">
                            Meilleures notes
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($books as $book)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="position-relative">
                                <img src="{{ $book->cover_url }}" class="card-img-top" alt="{{ $book->title }}" style="height: 200px; object-fit: cover;">
                                @if($book->reviews_avg_rating)
                                    <div class="position-absolute top-0 end-0 bg-warning text-white px-2 py-1 m-2 rounded">
                                        {{ number_format($book->reviews_avg_rating, 1) }} <i class="fas fa-star"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $book->title }}</h5>
                                <p class="card-subtitle mb-2 text-muted small">{{ $book->author }}</p>
                                <p class="card-text text-muted small mb-2">
                                    <i class="fas fa-calendar-alt"></i> {{ $book->published_at instanceof \Carbon\Carbon ? $book->published_at->format('d/m/Y') : date('d/m/Y', strtotime($book->published_at)) }} 
                                    @if($book->category)
                                        <span class="mx-2">|</span>
                                        <i class="fas fa-tag"></i> {{ $book->category->name }}
                                    @endif
                                </p>
                                
                                <!-- Rating Display -->
                                <div class="mb-3">
                                    @if($book->reviews_count > 0)
                                        <div class="star-rating d-inline-block">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($book->reviews_avg_rating))
                                                    <i class="fas fa-star text-warning"></i>
                                                @elseif($i == ceil($book->reviews_avg_rating) && ($book->reviews_avg_rating - floor($book->reviews_avg_rating)) >= 0.5)
                                                    <i class="fas fa-star-half-alt text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="text-muted small ms-1">
                                            ({{ $book->reviews_count }} avis)
                                        </span>
                                    @else
                                        <span class="text-muted small">Pas encore noté</span>
                                    @endif
                                </div>
                                
                                <p class="card-text text-truncate" style="max-height: 3.6em;">{{ $book->description }}</p>
                            </div>
                            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                                <a href="{{ route('user.show', $book) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-info-circle"></i> Détails
                                </a>
                                @auth
                                    @if(!$book->has_user_review)
                                        <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $book->id }}">
                                            <i class="fas fa-star"></i> Noter
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>

                    <!-- Review Modal -->
                    @auth
                        @if(!$book->has_user_review)
                            <div class="modal fade" id="reviewModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light">
                                            <h5 class="modal-title">Votre avis sur "{{ $book->title }}"</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('user.books.reviews.store', $book) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">Note</label>
                                                    <div class="rating">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <input type="radio" id="star{{ $i }}-{{ $book->id }}" 
                                                                   name="rating" value="{{ $i }}"
                                                                   {{ old('rating') == $i ? 'checked' : '' }} required>
                                                            <label for="star{{ $i }}-{{ $book->id }}"><i class="fas fa-star"></i></label>
                                                        @endfor
                                                    </div>
                                                    @error('rating')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="comment-{{ $book->id }}" class="form-label fw-bold">Commentaire (optionnel)</label>
                                                    <textarea class="form-control" id="comment-{{ $book->id }}" 
                                                              name="comment" rows="4" maxlength="500"
                                                              placeholder="Dites-nous ce que vous avez pensé de ce livre...">{{ old('comment') }}</textarea>

                                                    <div class="form-text small text-end">500 caractères maximum</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-paper-plane me-1"></i> Envoyer
                                                </button>
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
            <div class="d-flex justify-content-center mt-4">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating {
        display: flex;
        justify-content: flex-start;
        gap: 5px;
    }
    .rating input {
        display: none;
    }
    .rating label {
        cursor: pointer;
        font-size: 1.5rem;
        color: #ddd;
        transition: color 0.2s;
    }
    .rating input:checked ~ label,
    .rating label:hover,
    .rating input:hover ~ label {
        color: #ffc107;
    }
    .star-rating {
        font-size: 0.9rem;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .text-truncate {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@endsection