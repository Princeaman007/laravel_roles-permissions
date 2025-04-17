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
                        <a href="{{ route('account.addresses.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Ajouter une adresse
                        </a>
                    </div>
                    <p class="card-text">Voici vos adresses enregistrées.</p>

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    <!-- Liste des adresses -->
                    <div class="row">
                        @forelse($addresses ?? [] as $address)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $address->name ?? '' }}</h5>
                                    <p class="card-text">
                                        {{ $address->address_line1 }}<br>
                                        @if($address->address_line2)
                                        {{ $address->address_line2 }}<br>
                                        @endif
                                        {{ $address->postal_code }} {{ $address->city }}<br>
                                        {{ $address->state }}, {{ $address->country }}<br>
                                        <abbr title="Téléphone">Tél:</abbr> {{ $address->phone }}
                                    </p>

                                    <!-- Type d'adresse -->
                                    <p class="mb-2">
                                        @if($address->type === 'shipping')
                                        <span class="badge bg-info">Adresse de livraison</span>
                                        @elseif($address->type === 'billing')
                                        <span class="badge bg-warning">Adresse de facturation</span>
                                        @else
                                        <span class="badge bg-secondary">Livraison et facturation</span>
                                        @endif
                                    </p>

                                    <!-- Adresse par défaut -->
                                    @if($address->is_default)
                                    <p class="mb-2">
                                        <span class="badge bg-success">Adresse par défaut</span>
                                    </p>
                                    @endif
                                </div>
                                <div class="card-footer bg-white d-flex justify-content-between">
                                    <a href="{{ route('account.addresses.edit', $address->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    @if(($addresses ?? collect([]))->count() > 1)
                                    <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse?');">
                                            <i class="fas fa-trash-alt me-1"></i>Supprimer
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>Vous n'avez pas encore ajouté d'adresse.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection