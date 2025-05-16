@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8 mx-auto">
            <!-- Search Bar -->
            <form action="{{ route('books.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="query" class="form-control" placeholder="Rechercher un livre par titre, auteur...">
                    <button class="btn btn-primary" type="submit">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Books Section -->
    <section class="mb-5">
        <h2 class="mb-4">Livres récents</h2>
        <div class="row">
            @foreach($recentBooks as $book)
                <div class="col-md-3 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>
    </section>

    <!-- Popular Books Section -->
    <section>
        <h2 class="mb-4">Livres populaires</h2>
        <div class="row">
            @foreach($popularBooks as $book)
                <div class="col-md-3 mb-4">
                    @include('partials.book-card', ['book' => $book])
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection