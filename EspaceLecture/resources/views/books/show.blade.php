@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="md:flex">
            <!-- Couverture du livre -->
            <div class="md:w-1/3">
                @if($book->cover)
                    <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}" class="w-full h-auto">
                @else
                    <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">Pas de couverture</span>
                    </div>
                @endif
            </div>
            
            <!-- Détails du livre -->
            <div class="md:w-2/3 p-6">
                <h1 class="text-3xl font-bold mb-2">{{ $book->title }}</h1>
                <p class="text-gray-600 mb-1"><span class="font-semibold">Auteur:</span> {{ $book->author }}</p>
                <p class="text-gray-600 mb-1"><span class="font-semibold">Catégorie:</span> {{ $book->category->name }}</p>
                <p class="text-gray-600 mb-1"><span class="font-semibold">Pages:</span> {{ $book->pages }}</p>
                <p class="text-gray-600 mb-1"><span class="font-semibold">Date de publication:</span> {{ $book->published_at->format('d/m/Y') }}</p>
                <p class="text-gray-600 mb-4"><span class="font-semibold">Statut:</span> 
                    <span class="{{ $book->status == 'available' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $book->status == 'available' ? 'Disponible' : 'Emprunté' }}
                    </span>
                </p>
                
                <!-- Note moyenne -->
                <div class="flex items-center mb-4">
                    <div class="flex mr-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($book->average_rating))
                                <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @elseif($i == ceil($book->average_rating) && $book->average_rating - floor($book->average_rating) >= 0.5)
                                <svg class="w-6 h-6 text-yellow-400" fill="half" viewBox="0 0 20 20">
                                    <defs>
                                        <linearGradient id="half-star">
                                            <stop offset="50%" stop-color="currentColor"></stop>
                                            <stop offset="50%" stop-color="gray" stop-opacity="0.5"></stop>
                                        </linearGradient>
                                    </defs>
                                    <path fill="url(#half-star)" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            @endif
                        @endfor
                    </div>
                    <span class="text-gray-600">{{ number_format($book->average_rating, 1) }}/5 ({{ $book->reviews->count() }} avis)</span>
                </div>
                
                <!-- Résumé -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-2">Résumé</h2>
                    <p class="text-gray-700">{{ $book->summary ?? 'Aucun résumé disponible.' }}</p>
                </div>
                
                <!-- Actions -->
                <div class="flex space-x-4">
                    @can('update', $book)
                        <a href="{{ route('books.edit', $book) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                            Modifier
                        </a>
                    @endcan
                    
                    @can('delete', $book)
                        <form action="{{ route('books.destroy', $book) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                                Supprimer
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
        
        <!-- Commentaires -->
        <div class="border-t border-gray-200 p-6">
            <h2 class="text-2xl font-bold mb-4">Avis des lecteurs</h2>
            
            @auth
                <!-- Formulaire d'avis -->
                @if(!$book->reviews->where('user_id', auth()->id())->count())
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-lg font-semibold mb-3">Donnez votre avis</h3>
                        <form action="{{ route('reviews.store', $book) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Note</label>
                                <div class="flex">
                                    @for($i = 1; $i <= 5; $i++)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" class="hidden" {{ $i == 3 ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" class="cursor-pointer">
                                            <svg class="w-8 h-8" fill="{{ $i <= 3 ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 mb-2">Commentaire (optionnel)</label>
                                <textarea name="comment" id="comment" rows="3" class="w-full border rounded-lg px-3 py-2"></textarea>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                Envoyer l'avis
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="bg-blue-50 p-4 rounded-lg mb-6">
                    <p class="text-blue-800">Vous devez <a href="{{ route('login') }}" class="text-blue-600 underline">vous connecter</a> pour laisser un avis.</p>
                </div>
            @endauth
            
            <!-- Liste des commentaires -->
            @if($book->reviews->count())
                <div class="space-y-4">
                    @foreach($book->reviews as $review)
                        <div class="border-b border-gray-200 pb-4 last:border-0">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-gray-500 text-sm">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            @endif
                            
                            @can('delete', $review)
                                <div class="mt-2 text-right">
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 text-sm hover:text-red-700">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500">Aucun avis pour ce livre pour le moment.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('input[name="rating"]').forEach(input => {
        input.addEventListener('change', function() {
            const rating = parseInt(this.value);
            const stars = document.querySelectorAll('label[for^="star"] svg');
            
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.setAttribute('fill', 'currentColor');
                } else {
                    star.setAttribute('fill', 'none');
                }
            });
        });
    });
</script>
@endpush