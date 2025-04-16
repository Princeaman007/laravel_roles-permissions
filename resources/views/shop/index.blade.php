@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- 🔍 Sidebar Filtres --}}
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-sliders"></i> Filtres
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.index') }}" method="GET" class="small">
                        <div class="mb-3">
                            <label for="price_min" class="form-label">Prix minimum</label>
                            <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                        </div>
                        <div class="mb-3">
                            <label for="price_max" class="form-label">Prix maximum</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-funnel"></i> Appliquer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 🛍 Section Produits --}}
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 fw-bold mb-0">Tous nos produits</h2>
                <div class="d-flex align-items-center">
                    <label for="sort" class="me-2 text-muted small">Trier par :</label>
                    <select id="sort" class="form-select form-select-sm" style="width: 200px;"
                            onchange="window.location.href = this.value">
                        <option value="{{ route('shop.index', ['sort' => 'name_asc']) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nom (A-Z)</option>
                        <option value="{{ route('shop.index', ['sort' => 'name_desc']) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nom (Z-A)</option>
                        <option value="{{ route('shop.index', ['sort' => 'price_asc']) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (croissant)</option>
                        <option value="{{ route('shop.index', ['sort' => 'price_desc']) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (décroissant)</option>
                        <option value="{{ route('shop.index', ['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Plus récent</option>
                    </select>
                </div>
            </div>

            {{-- 📦 Liste Produits --}}
            <div class="row">
                @forelse ($products ?? [] as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-0">
                            {{-- ✅ Image produit avec gestion propre --}}
                            @if ($product->image)
                                <div class="d-flex align-items-center justify-content-center bg-white" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="img-fluid"
                                         style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <span class="text-muted">Pas d'image</span>
                                </div>
                            @endif

                            {{-- ✅ Infos produit --}}
                            <div class="card-body">
                                <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                                <p class="card-text text-muted small">
                                    {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold text-primary">{{ number_format($product->price ?? 0, 2) }} €</span>
                                    <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                        Voir
                                    </a>
                                </div>

                                {{-- ❤️ Wishlist --}}
                                <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="bi bi-heart"></i> Ajouter à la wishlist
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            Aucun produit disponible. Essayez de modifier vos filtres ou revenez plus tard.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- 📍 Pagination --}}
            @if (isset($products) && method_exists($products, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
