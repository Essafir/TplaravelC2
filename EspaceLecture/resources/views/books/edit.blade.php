@extends('layouts.app')

@section('title', isset($book) ? 'Modifier un livre' : 'Ajouter un livre')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">{{ isset($book) ? 'Modifier le livre' : 'Ajouter un nouveau livre' }}</h1>
            
            <form action="{{ isset($book) ? route('books.update', $book) : route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if(isset($book))
                    @method('PUT')
                @endif
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Titre -->
                    <div>
                        <label for="title" class="block text-gray-700 mb-2">Titre *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $book->title ?? '') }}" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Auteur -->
                    <div>
                        <label for="author" class="block text-gray-700 mb-2">Auteur *</label>
                        <input type="text" id="author" name="author" value="{{ old('author', $book->author ?? '') }}" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('author')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Catégorie -->
                    <div>
                        <label for="category_id" class="block text-gray-700 mb-2">Catégorie *</label>
                        <select id="category_id" name="category_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionnez une catégorie</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Statut -->
                    <div>
                        <label for="status" class="block text-gray-700 mb-2">Statut *</label>
                        <select id="status" name="status" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="available" {{ old('status', $book->status ?? '') == 'available' ? 'selected' : '' }}>Disponible</option>
                            <option value="borrowed" {{ old('status', $book->status ?? '') == 'borrowed' ? 'selected' : '' }}>Emprunté</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Nombre de pages -->
                    <div>
                        <label for="pages" class="block text-gray-700 mb-2">Nombre de pages *</label>
                        <input type="number" id="pages" name="pages" value="{{ old('pages', $book->pages ?? '') }}" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('pages')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date de publication -->
                    <div>
                        <label for="published_at" class="block text-gray-700 mb-2">Date de publication *</label>
                        <input type="date" id="published_at" name="published_at" value="{{ old('published_at', isset($book) ? $book->published_at->format('Y-m-d') : '' }}" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('published_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Image de couverture -->
                    <div class="md:col-span-2">
                        <label for="cover" class="block text-gray-700 mb-2">Image de couverture</label>
                        <input type="file" id="cover" name="cover" accept="image/jpeg,image/png,image/jpg" 
                               class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('cover')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        @if(isset($book) && $book->cover)
                            <div class="mt-4">
                                <p class="text-gray-600 mb-2">Image actuelle :</p>
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="Couverture actuelle" class="w-32 h-auto">
                            </div>
                        @endif
                    </div>
                    
                    <!-- Résumé -->
                    <div class="md:col-span-2">
                        <label for="summary" class="block text-gray-700 mb-2">Résumé</label>
                        <textarea id="summary" name="summary" rows="4" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('summary', $book->summary ?? '') }}</textarea>
                        @error('summary')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('books.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        {{ isset($book) ? 'Mettre à jour' : 'Ajouter' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection