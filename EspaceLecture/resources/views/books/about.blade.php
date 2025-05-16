@extends('layouts.public')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">À propos d'EspaceLecture</div>

                <div class="card-body">
                    <h3>Notre mission</h3>
                    <p class="mb-4">
                        EspaceLecture est une plateforme dédiée aux amoureux de la lecture. 
                        Notre objectif est de créer une communauté où les lecteurs peuvent 
                        découvrir de nouveaux livres, partager leurs avis et trouver leur 
                        prochaine lecture.
                    </p>

                    <h3>Contact</h3>
                    <p>
                        <strong>Email:</strong> contact@espacelecture.com<br>
                        <strong>Téléphone:</strong> +33 1 23 45 67 89<br>
                        <strong>Adresse:</strong> 123 Rue des Livres, Paris, France
                    </p>

                    <hr>

                    <h3>Envoyez-nous un message</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Votre nom</label>
                            <input type="text" class="form-control" id="name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Votre email</label>
                            <input type="email" class="form-control" id="email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea class="form-control" id="message" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection