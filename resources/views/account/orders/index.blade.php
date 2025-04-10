@extends('layouts.app')

@section('title', 'Mes Commandes')

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
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Historique de mes commandes</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(count($orders) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>N° de commande</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>{{ $order->order_number ?? $order->id }}</strong>
                                            </td>
                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                            <td>
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
                                            </td>
                                            <td>{{ number_format($order->total, 2) }} €</td>
                                            <td>
                                                <a href="{{ route('account.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Détails
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-shopping-bag fa-5x text-muted"></i>
                            </div>
                            <h2>Aucune commande</h2>
                            <p class="lead mb-4">Vous n'avez pas encore passé de commande.</p>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Commencer vos achats
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection