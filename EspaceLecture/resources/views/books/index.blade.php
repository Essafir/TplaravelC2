@extends('layouts.app')

@section('title', 'Liste des Livres')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Liste des Livres</h1>
        
        @can('create', App\Models\Book::class)
            <a href="{{ route('books.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ajouter un livre
            </a>
        @endcan
    </div>

    <!-- Filtres -->
    <div class="bg-gray-100 p-4 rounded-lg mb-6">
        <form action="{{ route('books.index') }}" method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">
            <div class="flex-1">
                <input type="text" name="search" placeholder="Rechercher par titre ou auteur" 
                       class="w-full border rounded-lg px-3 py-2" 
                       value="{{ request('search') }}">
            </div>
            
            <div>
                <select name="category" class="border rounded-lg px-3 py-2">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select name="status" class="border rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Emprunté</option>
                </select>
            </div>
            
            <div>
                <select name="sort" class="border rounded-lg px-3 py-2">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récents</option>
                    <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Par titre</option>
                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Meilleures notes</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Filtrer
            </button>
            
            <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Réinitialiser
            </a>
        </form>
    </div>

    <!-- Liste des livres -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($books as $book)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Pas de couverture</span>
                    </div>
                @endif
                
                <div class="p-4">
                    <h2 class="text-xl font-bold mb-2">{{ $book->title }}</h2>
                    <p class="text-gray-600 mb-1">Auteur: {{ $book->author }}</p>
                    <p class="text-gray-600 mb-1">Catégorie: {{ $book->category->name }}</p>
                    <p class="text-gray-600 mb-1">Pages: {{ $book->pages }}</p>
                    <p class="text-gray-600 mb-3">Publié le: {{ $book->published_at->format('d/m/Y') }}</p>
                    
                    <!-- Note moyenne -->
                    <div class="flex items-center mb-3">
                        <div class="flex">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($book->average_rating))
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @elseif($i == ceil($book->average_rating) && $book->average_rating - floor($book->average_rating) >= 0.5)
                                    <svg class="w-5 h-5 text-yellow-400" fill="half" viewBox="0 0 20 20">
                                        <defs>
                                            <linearGradient id="half-star">
                                                <stop offset="50%" stop-color="currentColor"></stop>
                                                <stop offset="50%" stop-color="gray" stop-opacity="0.5"></stop>
                                            </linearGradient>
                                        </defs>
                                        <path fill="url(#half-star)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                        </div>
                        <span class="ml-2 text-gray-600">{{ number_format($book->average_rating, 1) }}/5 ({{ $book->reviews->count() }} avis)</span>
                    </div>
                    
                    <div class="flex justify-between items-center">
                        <a href="{{ route('books.show', $book) }}" class="text-blue-500 hover:text-blue-700 font-medium">
                            Voir détails
                        </a>
                        
                        @can('update', $book)
                            <a href="{{ route('books.edit', $book) }}" class="text-yellow-500 hover:text-yellow-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8">
                <p class="text-gray-500">Aucun livre trouvé.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $books->links() }}
    </div>
</div>
@endsection