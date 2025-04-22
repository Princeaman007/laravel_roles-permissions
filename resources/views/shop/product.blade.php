@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Boutique</a></li>
                    @if(isset($product->category))
                        <li class="breadcrumb-item">
                            <a href="{{ route('shop.category', $product->category->slug) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- Fiche produit --}}
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0">
                @if ($product->image)
                    <div class="d-flex align-items-center justify-content-center bg-white" style="height: 400px;">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                             class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <span class="text-muted">Pas d'image disponible</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <h1 class="mb-3 fw-bold">{{ $product->name }}</h1>

            <div class="mb-3">
                @if($product->discount_price)
                    <h3 class="text-danger fw-bold mb-1">{{ number_format($product->discount_price, 2) }} €</h3>
                    <div>
                        <span class="text-muted text-decoration-line-through me-2">{{ number_format($product->price, 2) }} €</span>
                        <span class="badge bg-danger">
                            -{{ round((1 - $product->discount_price / $product->price) * 100) }}%
                        </span>
                    </div>
                @else
                    <h3 class="text-primary fw-bold mb-1">{{ number_format($product->price ?? 0, 2) }} €</h3>
                @endif
            </div>
            

            @if($product->stock > 0)
                <div class="mb-3">
                    <span class="badge bg-success">En stock</span>
                    <small class="text-muted ms-2">{{ $product->stock }} unités disponibles</small>
                </div>
            @else
                <div class="mb-3">
                    <span class="badge bg-danger">Rupture de stock</span>
                </div>
            @endif

            <p class="text-muted mb-4">{{ $product->description }}</p>

            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row align-items-center g-3">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Quantité:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="quantity" name="quantity"
                                   class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            {{-- Caractéristiques --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-semibold">
                    Caractéristiques
                </div>
                <div class="card-body">
                    <table class="table table-striped mb-0">
                        <tbody>
                            @if(isset($product->sku))
                                <tr><th scope="row">SKU</th><td>{{ $product->sku }}</td></tr>
                            @endif
                            @if(isset($product->weight))
                                <tr><th scope="row">Poids</th><td>{{ $product->weight }} kg</td></tr>
                            @endif
                            @if(isset($product->dimensions))
                                <tr><th scope="row">Dimensions</th><td>{{ $product->dimensions }}</td></tr>
                            @endif
                            @if(isset($product->category))
                                <tr><th scope="row">Catégorie</th><td>{{ $product->category->name }}</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Produits similaires --}}
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="row mt-5">
            <div class="col-12 mb-3">
                <h3 class="fw-bold">Produits similaires</h3>
            </div>

            @foreach($relatedProducts as $relatedProduct)
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        @if ($relatedProduct->image)
                            <div class="d-flex align-items-center justify-content-center bg-white" style="height: 160px;">
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}"
                                     class="img-fluid"
                                     style="max-height: 100%; max-width: 100%; object-fit: contain;"
                                     alt="{{ $relatedProduct->name }}">
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 160px;">
                                <span class="text-muted">Pas d'image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h6 class="card-title text-truncate" title="{{ $relatedProduct->name }}">{{ $relatedProduct->name }}</h6>
                            <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($relatedProduct->description, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semibold text-primary">{{ number_format($relatedProduct->price ?? 0, 2) }} €</span>
                                <a href="{{ route('shop.product', $relatedProduct->slug) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
