@extends('layouts.admin')
@section('title', 'Tableau de bord Admin')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h2 mb-4">Tableau de bord Administrateur</h1>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Livres</h5>
                    <p class="display-4 text-primary">{{ $stats['totalBooks'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs</h5>
                    <p class="display-4 text-success">{{ $stats['totalUsers'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Catégories</h5>
                    <p class="display-4 text-info">{{ $stats['totalCategories'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Livres récemment ajoutés -->
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="h4 mb-0">Livres récemment ajoutés</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Date</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recentBooks'] as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>{{ \Carbon\Carbon::parse($book->published_at)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge {{ $book->status == 'available' ? 'badge-success' : 'badge-danger' }}">
                                        {{ $book->status == 'available' ? 'Disponible' : 'Emprunté' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Livres les mieux notés -->
    <div class="card">
        <div class="card-header">
            <h2 class="h4 mb-0">Livres les mieux notés</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Auteur</th>
                            <th>Note moyenne</th>
                            <th>Nombre d'avis</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['topRatedBooks'] as $book)
                            <tr>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="mr-2 text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= floor($book->reviews_avg_rating) ? '' : ($i == ceil($book->reviews_avg_rating) && ($book->reviews_avg_rating - floor($book->reviews_avg_rating)) >= 0.5 ? '-half-alt' : '') }}"></i>
                                            @endfor
                                        </div>
                                        <span>{{ number_format($book->reviews_avg_rating, 1) }}/5</span>
                                    </div>
                                </td>
                                <td>{{ $book->reviews_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection