@extends('layouts.app')

@section('title', 'Détails de la commande #' . ($order->order_number ?? $order->id))

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
                    <a href="{{ route('account.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </a>
                    <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Commande #{{ $order->order_number ?? $order->id }}</h2>
                <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour aux commandes
                </a>
            </div>

            <div class="row mb-4">
                <!-- Informations de la commande -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Informations de la commande</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Date de commande:</span>
                                    <span>{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Statut:</span>
                                    @php
                                    $statusClass = 'bg-secondary';

                                    if($order->status == 'completed') {
                                    $statusClass = 'bg-success';
                                    } elseif($order->status == 'processing') {
                                    $statusClass = 'bg-primary';
                                    } elseif($order->status == 'pending') {
                                    $statusClass = 'bg-warning';
                                    } elseif($order->status == 'cancelled') {
                                    $statusClass = 'bg-danger';
                                    }
                                    @endphp

                                    <span class="badge {{ $statusClass }}">
                                        @switch($order->status)
                                        @case('pending')
                                        En attente
                                        @break
                                        @case('processing')
                                        En traitement
                                        @break
                                        @case('completed')
                                        Terminée
                                        @break
                                        @case('cancelled')
                                        Annulée
                                        @break
                                        @default
                                        {{ $order->status }}
                                        @endswitch
                                    </span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Mode de paiement:</span>
                                    <span>{{ $order->payment_method ?? 'Non spécifié' }}</span>
                                </li>
                                @if($order->payment_id)
                                <li class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">ID Transaction:</span>
                                    <span>{{ $order->payment_id }}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Adresse de livraison</h5>
                        </div>
                        <div class="card-body">
                            @if($order->shippingAddress)
                                <address class="mb-0">
                                    <strong>{{ $order->shippingAddress->full_name ?? $order->shippingAddress->name }}</strong><br>
                                    {{ $order->shippingAddress->address_line1 }}<br>
                                    @if($order->shippingAddress->address_line2)
                                        {{ $order->shippingAddress->address_line2 }}<br>
                                    @endif
                                    {{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}<br>
                                    {{ $order->shippingAddress->country }}<br>
                                    @if($order->shippingAddress->phone)
                                        <abbr title="Téléphone">Tél:</abbr> {{ $order->shippingAddress->phone }}
                                    @endif
                                </address>
                            @elseif(isset($defaultShippingAddress) && $defaultShippingAddress)
                                <address class="mb-0">
                                    <strong>{{ $defaultShippingAddress->full_name ?? $defaultShippingAddress->name }}</strong><br>
                                    {{ $defaultShippingAddress->address_line1 }}<br>
                                    @if($defaultShippingAddress->address_line2)
                                        {{ $defaultShippingAddress->address_line2 }}<br>
                                    @endif
                                    {{ $defaultShippingAddress->postal_code }} {{ $defaultShippingAddress->city }}<br>
                                    {{ $defaultShippingAddress->country }}<br>
                                    @if($defaultShippingAddress->phone)
                                        <abbr title="Téléphone">Tél:</abbr> {{ $defaultShippingAddress->phone }}
                                    @endif
                                </address>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-map-marker-alt fa-2x text-muted mb-2"></i>
                                    <p class="mb-0">Aucune adresse de livraison disponible pour cette commande.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
            </div>

            <!-- Produits commandés -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Produits commandés</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Produit</th>
                                    <th class="text-center">Prix unitaire</th>
                                    <th class="text-center">Quantité</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(isset($item->product) && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product_name }}" class="img-thumbnail me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                            <div class="bg-light me-3" style="width: 50px; height: 50px;"></div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $item->product_name }}</h6>
                                                @if($item->options)
                                                <small class="text-muted">{{ $item->options }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ number_format($item->price, 2) }} €</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Résumé de la commande -->
            <div class="row">
                <div class="col-md-6 ms-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Résumé</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Sous-total:</span>
                                    <span>{{ number_format($order->subtotal, 2) }} €</span>
                                </li>
                                @if($order->shipping_cost > 0)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Frais de livraison:</span>
                                    <span>{{ number_format($order->shipping_cost, 2) }} €</span>
                                </li>
                                @endif
                                @if($order->tax > 0)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>TVA ({{ $order->tax_rate ?? '20' }}%):</span>
                                    <span>{{ number_format($order->tax, 2) }} €</span>
                                </li>
                                @endif
                                @if($order->discount > 0)
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Remise:</span>
                                    <span>-{{ number_format($order->discount, 2) }} €</span>
                                </li>
                                @endif
                                <li class="d-flex justify-content-between border-top pt-2 mt-2">
                                    <strong>Total:</strong>
                                    <strong>{{ number_format($order->total, 2) }} €</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection