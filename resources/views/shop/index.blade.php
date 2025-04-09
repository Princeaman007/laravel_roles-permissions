@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Barre latérale pour les filtres -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Catégories</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($categories as $category)
                                <li class="list-group-item">
                                    <a href="{{ route('shop.category', $category->slug) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">Filtrer par prix</div>
                    <div class="card-body">
                        <form action="{{ route('shop.index') }}" method="get">
                            <div class="mb-3">
                                <label for="price_min" class="form-label">Prix minimum</label>
                                <input type="number" class="form-control" id="price_min" name="price_min" 
                                    value="{{ request('price_min') }}">
                            </div>
                            <div class="mb-3">
                                <label for="price_max" class="form-label">Prix maximum</label>
                                <input type="number" class="form-control" id="price_max" name="price_max"
                                    value="{{ request('price_max') }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Filtrer</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Liste des produits -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Nos produits</h2>
                    <div>
                        <select class="form-select" onchange="window.location.href=this.value">
                            <option value="{{ route('shop.index', ['sort' => 'newest']) }}" 
                                {{ request('sort') == 'newest' ? 'selected' : '' }}>
                                Les plus récents
                            </option>
                            <option value="{{ route('shop.index', ['sort' => 'price_asc']) }}"
                                {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                Prix croissant
                            </option>
                            <option value="{{ route('shop.index', ['sort' => 'price_desc']) }}"
                                {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                Prix décroissant
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    @forelse($products as $product)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                @else
                                    <div class="bg-light text-center py-5">
                                        <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ $product->category->name ?? 'Sans catégorie' }}
                                    </p>
                                    <p class="card-text">
                                        {{ Str::limit($product->short_description, 100) }}
                                    </p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @if($product->discount_price)
                                                    <span class="text-decoration-line-through">{{ $product->price }} €</span>
                                                    <span class="text-danger fw-bold">{{ $product->discount_price }} €</span>
                                                @else
                                                    <span class="fw-bold">{{ $product->price }} €</span>
                                                @endif
                                            </div>
                                            <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center">Aucun produit trouvé</p>
                        </div>
                    @endforelse
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection