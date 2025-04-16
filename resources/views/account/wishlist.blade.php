@extends('layouts.app')

@section('title', 'Ma Wishlist')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Ma Wishlist</span>
                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-sm">&larr; Retour aux produits</a>
            </div>
            
            <div class="card-body">
                @if ($wishlistItems->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted mb-3">Votre wishlist est vide.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                            Découvrir des produits
                        </a>
                    </div>
                @else
                    <div class="row">
                        @foreach ($wishlistItems as $item)
                            @php $product = $item->product; @endphp
                            @if ($product) {{-- ⚠️ Sécurité au cas où le produit ait été supprimé --}}
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100 border-light shadow-sm">
                                    <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/150' }}"
                                         class="card-img-top"
                                         alt="{{ $product->name }}"
                                         style="height: 160px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title text-truncate">{{ $product->name }}</h5>
                                        <p class="card-text text-primary fw-bold mb-2">
                                            {{ number_format($product->price, 2) }} €
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="{{ route('shop.product', $product->slug) }}" class="btn btn-primary">Voir</a>

                                            <form action="{{ route('wishlist.remove', ['product' => $product->id]) }}" method="POST" class="ms-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-heart-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        {{ $wishlistItems->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
