@extends('layouts.app')

@section('title', 'Panier d\'achat')


@section('content')
<div class="container py-5">
    <h1 class="mb-4">Votre Panier</h1>

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

    @if($cartItems->count() > 0)
        <div class="card mb-4 shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0">Produit</th>
                            <th class="border-0">Prix</th>
                            <th class="border-0" style="width: 140px;">Quantité</th>
                            <th class="border-0 text-end">Total</th>
                            <th class="border-0"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cartItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light me-3" style="width: 60px; height: 60px;"></div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->price, 2) }} €</td>
                                <td>
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="items[0][id]" value="{{ $item->id }}">
                                        <div class="input-group">
                                            <input type="number" name="items[0][quantity]" value="{{ $item->quantity }}" min="1" class="form-control">
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }} €</td>
                                <td class="text-end">
                                    <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce produit ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('shop.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Continuer les achats
                    </a>
                </div>
                <a href="{{ route('cart.clear') }}" class="btn btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier ?')">
                    <i class="fas fa-trash me-2"></i>Vider le panier
                </a>
            </div>
        </div>

        <!-- Résumé du panier -->
        <div class="row mt-4">
            <div class="col-md-6 ms-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Résumé de la commande</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sous-total:</span>
                            <span>{{ number_format($cart->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }} €</span>
                        </div>
                        <div class="d-flex justify-content-between border-top border-bottom py-3 my-2">
                            <span class="h5 mb-0">Total:</span>
                            <span class="h5 mb-0">{{ number_format($cart->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }} €</span>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-lock me-2"></i>Passer à la caisse
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
            </div>
            <h2>Votre panier est vide</h2>
            <p class="lead mb-4">Il semble que vous n'ayez pas encore ajouté d'articles à votre panier.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Découvrir nos produits
            </a>
        </div>
    @endif
</div>
@endsection