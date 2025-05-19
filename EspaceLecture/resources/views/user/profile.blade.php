@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row">
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3>Mon Profil</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Photo de profil</label>
                            <input type="file" name="avatar" class="form-control">
                            @if($user->avatar)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$user->avatar) }}" width="100">
                                </div>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3>Mes Avis ({{ $reviews->total() }})</h3>
                </div>
                <div class="card-body">
                    @foreach($reviews as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <h5>
                                    <a href="{{ route('books.show', $review->book) }}">{{ $review->book->title }}</a>
                                </h5>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="mt-2">{{ $review->comment }}</p>
                            @endif
                            <small class="text-muted">Posté le {{ $review->created_at->format('d/m/Y') }}</small>
                        </div>
                    @endforeach
                    
                    {{ $reviews->links() }}
                </div>
            </div>
            <div class="card mt-4">
    <div class="card-header">
        <h3>Historique de Recherche ({{ $searchHistory->total() }})</h3>
    </div>
    <div class="card-body">
        @if($searchHistory->isEmpty())
            <p class="text-muted">Aucun historique de recherche trouvé.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Recherche</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($searchHistory as $history)
                            <tr>
                                <td>
                                    <a href="{{ route('user.searchuser', ['query' => $history->query]) }}">
                                        {{ $history->query }}
                                    </a>
                                </td>search
                                    <td>
                                        {{ $history->searched_at instanceof \Carbon\Carbon ? $history->searched_at->format('d/m/Y H:i') : date('d/m/Y H:i', strtotime($history->searched_at)) }}
                                    </td>                                <td>
                                    <form action="{{ route('user.search-history.destroy', $history) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"  onclick="return confirm('Supprimer cette entrée?')">
                                            supprimée
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $searchHistory->links() }}
        @endif
    </div>
</div>
        </div>
    </div>
</div>
@endsection