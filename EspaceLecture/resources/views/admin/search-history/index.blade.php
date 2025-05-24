@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Historique des recherches</h1>
        <form action="{{ route('admin.search-history.clear-all') }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash"></i> Vider l'historique
            </button>
        </form>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Recherche</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.history', $history->user_id) }}">
                                    {{ $history->user->name }}
                                </a>
                            </td>
                            <td>{{ $history->query }}</td>
                            <td>{{ $history->searched_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <form action="{{ route('admin.search-history.destroy', $history->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $histories->links() }}
        </div>
    </div>
</div>
@endsection