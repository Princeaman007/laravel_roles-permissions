@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Catégories</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($categories ?? [] as $cat)
                            <li class="list-group-item">
                                <a href="{{ route('shop.category', $cat->slug) }}" class="text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item">Aucune catégorie disponible</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Filtres</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('shop.index') }}" method="GET">
                        <div class="mb-3">
                            <label for="price_min" class="form-label">Prix minimum</label>
                            <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                        </div>
                        <div class="mb-3">
                            <label for="price_max" class="form-label">Prix maximum</label>
                            <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Tous nos produits</h2>
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