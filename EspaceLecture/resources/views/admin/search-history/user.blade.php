@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Historique de {{ $userHistories->first()->user->name ?? 'Utilisateur' }}</h1>
        <a href="{{ route('admin.search-history.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
        </div>
    </div>
</div>
@endsection