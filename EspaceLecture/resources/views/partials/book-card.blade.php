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
    </div>
    <div class="card-footer bg-transparent">
        <small class="text-muted">
Publié le {{ $book->published_at instanceof \Carbon\Carbon ? $book->published_at->format('d/m/Y') : date('d/m/Y', strtotime($book->published_at)) }}        </small>
    </div>
</div>