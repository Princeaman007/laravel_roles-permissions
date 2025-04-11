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
                    <h2 class="card-title">Mes adresses</h2>
                    <p class="card-text">Voici vos adresses enregistrées.</p>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Liste des adresses -->
                    <ul class="list-group">
                        @forelse($addresses as $address)
                            <li class="list-group-item">
                                <strong>{{ $address->full_name }}</strong><br>
                                {{ $address->address_line1 }}<br>
                                @if($address->address_line2)
                                    {{ $address->address_line2 }}<br>
                                @endif
                                {{ $address->postal_code }} {{ $address->city }}<br>
                                {{ $address->country }}<br>
                                <abbr title="Téléphone">Tél:</abbr> {{ $address->phone }}<br>
                                
                                <!-- Adresse par défaut -->
                                @if($address->is_default_shipping || $address->is_default_billing)
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

            <!-- Bouton pour ajouter une nouvelle adresse -->
            <div class="text-end">
                <a href="{{ route('account.addresses.create') }}" class="btn btn-primary">Ajouter une nouvelle adresse</a>
            </div>
        </div>
    </div>
</div>
@endsection
