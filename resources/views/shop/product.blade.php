@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Boutique</a></li>
                    @if(isset($product->category))
                        <li class="breadcrumb-item"><a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                        <span class="text-muted">Pas d'image disponible</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            
            <div class="mb-3">
                <h3 class="text-primary">{{ number_format($product->price ?? 0, 2) }} €</h3>
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

            <div class="mb-4">
                <p>{{ $product->description }}</p>
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Quantité:</label>
                        </div>
                        <div class="col-auto">
                            <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Caractéristiques</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            @if(isset($product->sku))
                                <tr>
                                    <th scope="row">SKU</th>
                                    <td>{{ $product->sku }}</td>
                                </tr>
                            @endif
                            @if(isset($product->weight))
                                <tr>
                                    <th scope="row">Poids</th>
                                    <td>{{ $product->weight }} kg</td>
                                </tr>
                            @endif
                            @if(isset($product->dimensions))
                                <tr>
                                    <th scope="row">Dimensions</th>
                                    <td>{{ $product->dimensions }}</td>
                                </tr>
                            @endif
                            @if(isset($product->category))
                                <tr>
                                    <th scope="row">Catégorie</th>
                                    <td>{{ $product->category->name }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Produits connexes -->
    @if(isset($relatedProducts) && count($relatedProducts) > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3>Produits similaires</h3>
                <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                @if ($relatedProduct->image)
                                    <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top" alt="{{ $relatedProduct->name }}">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                        <span class="text-muted">Pas d'image</span>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                                    <p class="card-text">{{ \Illuminate\Support\Str::limit($relatedProduct->description, 50) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6">{{ number_format($relatedProduct->price ?? 0, 2) }} €</span>
                                        <a href="{{ route('shop.product', $relatedProduct->slug) }}" class="btn btn-sm btn-primary">Voir</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection