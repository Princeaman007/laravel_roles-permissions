@extends('layouts.app')

@section('title', 'Mes adresses')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mon Compte</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i>Mes commandes
                    </a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Mon profil
                    </a>
                    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-map-marker-alt me-2"></i>Mes adresses
                    </a>
                    <a href="{{ route('account.wishlist') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-heart me-2"></i>Ma liste de souhaits
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="list-group-item list-group-item-action text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i>Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="card-title">Mes adresses</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                            <i class="fas fa-plus me-2"></i>Ajouter une adresse
                        </button>
                    </div>
                    <p class="card-text">Voici vos adresses enregistrées.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Débogage temporaire -->
                    @if(isset($addresses))
                        <div class="alert alert-info">
                            Nombre d'adresses : {{ $addresses->count() }}
                        </div>
                    @endif

                    <!-- Liste des adresses -->
                    <ul class="list-group">
                        @forelse($addresses as $address)
                            <li class="list-group-item">
                                <strong>{{ $address->name ?? '' }}</strong><br>
                                {{ $address->address_line1 }}<br>
                                @if($address->address_line2)
                                    {{ $address->address_line2 }}<br>
                                @endif
                                {{ $address->postal_code }} {{ $address->city }}<br>
                                {{ $address->country }}<br>
                                <abbr title="Téléphone">Tél:</abbr> {{ $address->phone }}<br>
                                
                                <!-- Adresse par défaut -->
                                @if($address->is_default)
                                    <span class="badge bg-success">Adresse par défaut</span>
                                @endif

                                <!-- Boutons pour modifier ou supprimer -->
                                <div class="mt-3">
                                    <a href="{{ route('account.addresses.edit', $address) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                    <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Aucune adresse enregistrée.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Ajouter une adresse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulaire -->
                    <div class="form-group mb-3">
                        <label for="address_line1">Adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address_line1') is-invalid @enderror" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
                        @error('address_line1') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="address_line2">Complément d'adresse</label>
                        <input type="text" class="form-control @error('address_line2') is-invalid @enderror" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
                        @error('address_line2') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="postal_code">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                @error('postal_code') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state">Région/Province/État <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required>
                                @error('state') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Pays <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                @error('country') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">Nom de l'adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type">Type d'adresse <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Adresse de livraison</option>
                            <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Adresse de facturation</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Livraison et facturation</option>
                        </select>
                        @error('type') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">Définir comme adresse par défaut</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // En cas d'erreurs de validation, afficher la modal
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('addAddressModal'));
            modal.show();
        });
    @endif
</script>
@endsection