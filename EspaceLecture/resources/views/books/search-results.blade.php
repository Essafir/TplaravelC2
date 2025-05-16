@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <!-- Search Form -->
            <form action="{{ route('books.search') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" 
                           placeholder="Search by title, author, or keyword"
                           value="{{ request('query') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
                <div class="mt-2">
                    <a href="{{ route('books.advanced-search') }}" class="text-sm">Advanced Search</a>
                </div>
            </form>

            <!-- Search Results Info -->
            <div class="mb-3">
                @if(request()->has('query') || request()->hasAny(['category', 'year_from', 'year_to', 'pages_min', 'pages_max', 'rating']))
                    <p class="text-muted">
                        Showing {{ $books->total() }} result(s) for:
                        @if(request('query')) "{{ request('query') }}" @endif
                        @if(request('category')) in {{ \App\Models\Category::find(request('category'))->name }} @endif
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="row">
        @forelse($books as $book)
            <div class="col-md-3 mb-4">
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
                            <div>
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($book->reviews->avg('rating')))
                                        <i class="fas fa-star text-warning"></i>
                                    @else
                                        <i class="far fa-star text-warning"></i>
                                    @endif
                                @endfor
                                <small>({{ $book->reviews->count() }})</small>
                            </div>
                            <span class="badge bg-secondary">{{ $book->category->name }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <small class="text-muted">
                            Publié le {{ $book->published_at instanceof \Carbon\Carbon ? $book->published_at->format('d/m/Y') : date('d/m/Y', strtotime($book->published_at)) }}
                        </small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    No books found matching your search criteria.
                    <a href="{{ route('books.index') }}" class="alert-link">Browse all books</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $books->appends(request()->query())->links() }}
    </div>
</div>
@endsection