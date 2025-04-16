@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12 mb-4 text-center">
            <h2 class="fw-bold display-6">{{ $category->name ?? 'Catégorie' }}</h2>
            <p class="text-muted">Explorez notre sélection dans cette catégorie</p>
        </div>
    </div>

    <div class="row">
        @forelse ($products ?? [] as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    {{-- ✅ Image --}}
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

                    {{-- ✅ Contenu produit --}}
                    <div class="card-body">
                        <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <span class="fw-bold text-primary">{{ number_format($product->price, 2) }} €</span>
                            <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    Aucun produit disponible dans cette catégorie.
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if (isset($products) && method_exists($products, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
