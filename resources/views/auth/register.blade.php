@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center py-4">
                    <h4 class="fw-bold mb-0">📝 Créer un compte</h4>
                    <p class="text-muted small mb-0">Rejoignez-nous dès maintenant !</p>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Nom complet</label>
                            <input id="name" type="text"
                                   class="form-control form-control-lg @error('name') is-invalid @enderror"
                                   name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Adresse email</label>
                            <input id="email" type="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mot de passe</label>
                            <input id="password" type="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirmation -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-semibold">Confirmer le mot de passe</label>
                            <input id="password-confirm" type="password"
                                   class="form-control form-control-lg"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <!-- Bouton -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                ✅ S'inscrire
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Option déjà inscrit -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    Vous avez déjà un compte ?
                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold text-primary">Se connecter</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
