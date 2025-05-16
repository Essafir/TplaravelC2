@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Book Cover -->
            <div class="card mb-4">
                <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('images/default-book-cover.jpg') }}" 
                     class="card-img-top" 
                     alt="{{ $book->title }}">
            </div>
        </div>

        <div class="col-md-8">
            <!-- Book Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <h1 class="card-title">{{ $book->title }}</h1>
                    <h4 class="card-subtitle mb-3 text-muted">{{ $book->author }}</h4>

                    <div class="d-flex align-items-center mb-3">
                        <!-- Average Rating -->
                        <div class="me-3">
                            @include('partials.rating-stars', ['rating' => $book->averageRating()])
                            <span class="ms-1">({{ $book->reviews()->count() }} avis)</span>
                        </div>

                        <!-- Status Badge -->
                        <span class="badge bg-{{ $book->status == 'available' ? 'success' : 'warning' }}">
                            {{ $book->status == 'available' ? 'Disponible' : 'Emprunté' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Catégorie:</strong> {{ $book->category->name }}
                    </div>

                    <div class="mb-3">
                        <strong>Date de publication:</strong> {{ $book->published_at->format('d/m/Y') }}
                    </div>

                    <div class="mb-3">
                        <strong>Nombre de pages:</strong> {{ $book->pages }}
                    </div>

                    <div class="mb-3">
                        <h5>Résumé</h5>
                        <p>{{ $book->summary ?? 'Aucun résumé disponible pour ce livre.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Avis des lecteurs</h3>
                </div>
                <div class="card-body">
                    @auth
                        @if(!$userReview)
                            <!-- Add Review Form -->
                            <div class="mb-4">
                                <h5>Donnez votre avis</h5>
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">

                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Note</label>
                                        <select name="rating" id="rating" class="form-select" required>
                                            <option value="">Sélectionnez une note</option>
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Très bon</option>
                                            <option value="3">3 - Bon</option>
                                            <option value="2">2 - Moyen</option>
                                            <option value="1">1 - Mauvais</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Commentaire (optionnel)</label>
                                        <textarea name="comment" id="comment" rows="3" class="form-control" maxlength="1000"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Envoyer l'avis</button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}">Connectez-vous</a> pour laisser un avis sur ce livre.
                        </div>
                    @endauth

                    <!-- Reviews List -->
                    <div class="reviews-list">
                        @forelse($book->reviews()->with('user')->latest()->get() as $review)
                            <div class="review mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <span class="text-muted ms-2">
                                            {{ $review->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @include('partials.rating-stars', ['rating' => $review->rating])
                                </div>
                                @if($review->comment)
                                    <div class="mt-2">
                                        {{ $review->comment }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">
                                Aucun avis pour ce livre pour le moment.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection