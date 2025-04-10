@extends('layouts.app')

@section('title', 'Mon Compte')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar du compte -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Mon Compte</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i>Mes commandes
                    </a>
                    <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i>Mon profil
                    </a>
                    <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action">
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
        
        <!-- Contenu principal -->
        <div class="col-lg-9">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title">Bienvenue, {{ Auth::user()->name }}!</h2>
                    <p class="card-text">Voici votre tableau de bord personnel où vous pouvez gérer votre compte et suivre vos activités.</p>
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="row">
                <!-- Dernières commandes -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Dernières commandes</h5>
                            <a href="{{ route('account.orders') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="card-body">
                            @if(isset($recentOrders) && $recentOrders->count() > 0)
                                <div class="list-group">
                                    @foreach($recentOrders as $order)
                                        <a href="{{ route('account.orders.show', $order->id) }}" class="list-group-item list-group-item-action">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1">Commande #{{ $order->order_number }}</h6>
                                                <small>{{ $order->created_at->format('d/m/Y') }}</small>
                                            </div>
                                            <p class="mb-1">{{ $order->items_count }} article(s)</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">Statut: {{ $order->status }}</small>
                                                <span class="badge bg-primary">{{ number_format($order->total, 2) }} €</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <p>Vous n'avez pas encore passé de commande.</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                                        Découvrir nos produits
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Informations personnelles -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Informations personnelles</h5>
                            <a href="{{ route('account.profile') }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 40px;">
                                        <i class="fas fa-user text-primary fa-fw fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Nom</div>
                                        <div>{{ Auth::user()->name }}</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 40px;">
                                        <i class="fas fa-envelope text-primary fa-fw fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Email</div>
                                        <div>{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center mb-2">
                                    <div style="width: 40px;">
                                        <i class="fas fa-phone text-primary fa-fw fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Téléphone</div>
                                        <div>{{ Auth::user()->phone ?? 'Non renseigné' }}</div>
                                    </div>
                                </div>
                                
                                <div class="d-flex align-items-center">
                                    <div style="width: 40px;">
                                        <i class="fas fa-calendar text-primary fa-fw fa-lg"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Membre depuis</div>
                                        <div>{{ Auth::user()->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Adresse de livraison par défaut -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Adresse de livraison</h5>
                            <a href="{{ route('account.addresses') }}" class="btn btn-sm btn-outline-primary">Gérer</a>
                        </div>
                        <div class="card-body">
                            @if(isset($defaultShippingAddress))
                                <address>
                                    <strong>{{ $defaultShippingAddress->full_name }}</strong><br>
                                    {{ $defaultShippingAddress->address_line1 }}<br>
                                    @if($defaultShippingAddress->address_line2)
                                        {{ $defaultShippingAddress->address_line2 }}<br>
                                    @endif
                                    {{ $defaultShippingAddress->postal_code }} {{ $defaultShippingAddress->city }}<br>
                                    {{ $defaultShippingAddress->country }}<br>
                                    <abbr title="Téléphone">Tél:</abbr> {{ $defaultShippingAddress->phone }}
                                </address>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <p>Vous n'avez pas encore d'adresse de livraison.</p>
                                    <a href="{{ route('account.addresses.create') }}" class="btn btn-primary">
                                        Ajouter une adresse
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Liste de souhaits -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Liste de souhaits</h5>
                            <a href="{{ route('account.wishlist') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
                        </div>
                        <div class="card-body">
                            @if(isset($wishlistItems) && $wishlistItems->count() > 0)
                                <div class="row row-cols-2 g-3">
                                    @foreach($wishlistItems as $item)
                                        <div class="col">
                                            <div class="card h-100 border">
                                                <div class="p-2">
                                                    @if($item->product->image)
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 100px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light" style="height: 100px;"></div>
                                                    @endif
                                                </div>
                                                <div class="card-body p-2">
                                                    <h6 class="card-title text-truncate">{{ $item->product->name }}</h6>
                                                    <p class="card-text mb-0">{{ number_format($item->product->price, 2) }} €</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                                    <p>Votre liste de souhaits est vide.</p>
                                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                                        Parcourir les produits
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection