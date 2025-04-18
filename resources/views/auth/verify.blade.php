@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center py-4">
                    <h4 class="fw-bold mb-0">📧 Vérification de l'adresse email</h4>
                    <p class="text-muted small mb-0">Nous avons besoin de vérifier que votre email est correct</p>
                </div>

                <div class="card-body px-4 py-4 text-center">
                    @if (session('resent'))
                        <div class="alert alert-success">
                            ✅ Un nouveau lien de vérification a été envoyé à votre adresse email.
                        </div>
                    @endif

                    <p class="mb-4">
                        Avant de continuer, veuillez vérifier votre boîte de réception pour cliquer sur le lien de confirmation.
                    </p>

                    <p class="mb-4 text-muted">
                        Vous n'avez pas reçu l'email ?
                    </p>

                    <form method="POST" action="{{ route('verification.resend') }}" class="d-grid gap-2">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg">
                            🔁 Renvoyer le lien de vérification
                        </button>
                    </form>

                    <p class="text-muted small mt-4">
                        Assurez-vous de vérifier aussi vos spams ou courriers indésirables.
                    </p>
                </div>
            </div>

            <!-- Option retour -->
            <div class="text-center mt-4">
                <a href="{{ route('logout') }}" class="btn btn-link text-danger small"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    🔙 Se déconnecter
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
