@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- ✅ HERO --}}
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold">Découvrez nos meilleurs produits</h1>
        <p class="text-muted lead">Une sélection de qualité, au meilleur prix. Achetez en toute confiance.</p>
    </div>

    <div class="row">
        {{-- 🔐 Sidebar Admin --}}
        @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Super Admin'))
            <div class="col-md-3 mb-4">
                {{-- ✅ Status --}}
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- ✅ Bloc Admin --}}
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-gear"></i> Administration
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            @canany(['create-role', 'edit-role', 'delete-role'])
                                <a href="{{ route('roles.index') }}" class="btn btn-outline-primary">
                                    <i class="bi bi-person-fill-gear"></i> Gérer les rôles
                                </a>
                            @endcanany
                            @canany(['create-user', 'edit-user', 'delete-user'])
                                <a href="{{ route('users.index') }}" class="btn btn-outline-success">
                                    <i class="bi bi-people"></i> Gérer les utilisateurs
                                </a>
                            @endcanany
                            @canany(['create-product', 'edit-product', 'delete-product'])
                                <a href="{{ route('products.index') }}" class="btn btn-outline-warning">
                                    <i class="bi bi-bag"></i> Gérer les produits
                                </a>
                            @endcanany
                            <a href="{{ route('account.addresses') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-geo-alt"></i> Mes adresses
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ✅ Notifications --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">🛎 Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </div>
                    <div class="card-body p-2" style="max-height: 250px; overflow-y: auto;">
                        <ul class="list-group list-group-flush">
                            @forelse(auth()->user()->notifications->take(5) as $notification)
                                <li class="list-group-item small">
                                    <strong>{{ $notification->data['title'] }}</strong><br>
                                    <a href="{{ route('orders.show', $notification->data['order_id']) }}">
                                        Commande #{{ $notification->data['order_number'] }}
                                    </a> – {{ number_format($notification->data['total'], 2, ',', ' ') }} €
                                    <br>
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
            <div class="col-md-12">
        @endif

            {{-- 🔁 Barre de tri --}}
            <div class="d-flex justify-content-end align-items-center mb-4">
                <label for="sort" class="me-2 text-muted">Trier par :</label>
                <select id="sort" class="form-select form-select-sm" style="width: 200px;"
                        onchange="window.location.href = this.value">
                    <option value="{{ route('shop.index', ['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                    <option value="{{ route('shop.index', ['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                    <option value="{{ route('shop.index', ['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (↑)</option>
                    <option value="{{ route('shop.index', ['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (↓)</option>
                    <option value="{{ route('shop.index', ['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Nouveautés</option>
                </select>
            </div>

            {{-- 🛍 Cartes produits --}}
            <div class="row">
                @forelse ($products ?? [] as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            {{-- 🖼 Image --}}
                            @if ($product->image)
                                <div class="d-flex align-items-center justify-content-center bg-white" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid"
                                         style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">Pas d'image</span>
                                </div>
                            @endif

                            {{-- Contenu --}}
                            <div class="card-body">
                                <h5 class="card-title text-truncate" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h5>
                                <p class="card-text text-muted small">
                                    {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-primary">
                                        {{ number_format($product->price ?? 0, 2) }} €
                                    </span>
                                    <a href="{{ route('shop.product', $product->slug) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Voir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            Aucun produit disponible pour le moment.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- 📄 Pagination --}}
            @if (isset($products) && method_exists($products, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
