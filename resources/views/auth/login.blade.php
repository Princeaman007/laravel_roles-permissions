@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-white border-0 text-center py-4">
                    <h4 class="fw-bold mb-0">🔐 Connexion</h4>
                    <p class="text-muted mb-0 small">Accédez à votre compte pour continuer</p>
                </div>

                <div class="card-body px-4 py-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Adresse email</label>
                            <input id="email" type="email"
                                   class="form-control form-control-lg @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">Mot de passe</label>
                            <input id="password" type="password"
                                   class="form-control form-control-lg @error('password') is-invalid @enderror"
                                   name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Se souvenir de moi
                            </label>
                        </div>

                        <!-- Submit + Forgot -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                Connexion
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                    🔑 Mot de passe oublié ?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Option d'inscription -->
            <div class="text-center mt-4">
                <p class="small text-muted">
                    Vous n’avez pas encore de compte ?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">Créer un compte</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
