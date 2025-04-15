@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar - visible uniquement pour les administrateurs -->
        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
        <div class="col-md-3 mb-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Administration</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        @canany(['create-role', 'edit-role', 'delete-role'])
                            <a class="btn btn-outline-primary" href="{{ route('roles.index') }}">
                                <i class="bi bi-person-fill-gear"></i> Gérer les rôles
                            </a>
                        @endcanany
                        
                        @canany(['create-user', 'edit-user', 'delete-user'])
                            <a class="btn btn-outline-success" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> Gérer les utilisateurs
                            </a>
                        @endcanany
                        
                        @canany(['create-product', 'edit-product', 'delete-product'])
                            <a class="btn btn-outline-warning" href="{{ route('products.index') }}">
                                <i class="bi bi-bag"></i> Gérer les produits
                            </a>
                        @endcanany
                        
                        <a class="btn btn-outline-secondary" href="{{ route('account.addresses') }}">
                            <i class="bi bi-geo-alt"></i> Mes adresses
                        </a>
                    </div>
                </div>
            </div>
            
            <div id="product-filters"></div>
            
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">🛎 Notifications</h6>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </div>
                <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                    <ul class="list-group list-group-flush">
                        @forelse(auth()->user()->notifications->take(5) as $notification)
                            <li class="list-group-item small">
                                <div><strong>{{ $notification->data['title'] }}</strong></div>
                                <div>
                                    <a href="{{ route('orders.show', $notification->data['order_id']) }}">
                                        Commande #{{ $notification->data['order_number'] }}
                                    </a> – {{ number_format($notification->data['total'], 2, ',', ' ') }} €
                                </div>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Aucune notification</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
        @else
        <!-- Pour les utilisateurs normaux, utiliser toute la largeur -->
        <div class="col-md-12">
        @endif
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Nos produits</h2>
                <div class="d-flex align-items-center">
                    <label for="sort" class="me-2">Trier par:</label>
                    <select id="sort" class="form-select" onchange="window.location.href = this.value">
                        <option value="{{ route('shop.index', ['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="{{ route('shop.index', ['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                        <option value="{{ route('shop.index', ['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                        <option value="{{ route('shop.index', ['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                        <option value="{{ route('shop.index', ['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                    </select>
                </div>
            </div>

            <div class="row">
                @forelse ($products ?? [] as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">Pas d'image</span>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5">{{ number_format($product->price ?? 0, 2) }} €</span>
                                    <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-primary">Voir</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            Aucun produit disponible. Essayez de modifier vos filtres ou revenez plus tard.
                        </div>
                    </div>
                @endforelse
            </div>
            
            @if (isset($products) && method_exists($products, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection