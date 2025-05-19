<div class="card h-100">
    <a href="{{ route('books.show', $book) }}">
        <img src="{{ $book->cover ? asset('storage/' . $book->cover) : asset('images/default-book-cover.jpg') }}" 
             class="card-img-top" 
             alt="{{ $book->title }}" 
             style="height: 200px; object-fit: cover;">
    </a>
    <div class="card-body">
        <h5 class="card-title">
            <a href="{{ route('books.show', $book) }}" class="text-decoration-none">
                {{ Str::limit($book->title, 30) }}
            </a>
        </h5>
        <p class="card-text text-muted">{{ $book->author }}</p>
        
        <div class="d-flex justify-content-between align-items-center">
            @include('partials.rating-stars', ['rating' => $book->averageRating()])
            <span class="badge bg-secondary">{{ $book->category->name }}</span>
        </div>
        <div class="mb-2">
            @if($book->reviews_count > 0)
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++) 
                        @if($i <= floor($book->reviews_avg_rating))
                            <i class="fas fa-star text-warning"></i>
                        @elseif($i == ceil($book->reviews_avg_rating) && ($book->reviews_avg_rating - floor($book->reviews_avg_rating)) >= 0.5)
                            <i class="fas fa-star-half-alt text-warning"></i>
                        @else
                            <i class="far fa-star text-warning"></i>
                        @endif
                    @endfor
                    <span class="ms-1">
                        ({{ number_format($book->reviews_avg_rating, 1) }})
                        <small class="text-muted">({{ $book->reviews_count }} avis)</small>
                    </span>
                </div>
            @else
                <span class="text-muted">Pas encore noté</span>
            @endif
        </div>
    </div>
    <div class="card-footer bg-transparent">
        <small class="text-muted">
Publié le {{ $book->published_at instanceof \Carbon\Carbon ? $book->published_at->format('d/m/Y') : date('d/m/Y', strtotime($book->published_at)) }}        </small>
    </div>
</div>