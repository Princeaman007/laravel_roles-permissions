@if (auth()->check())
<div class="row mt-4">
    <div class="col-md-12 text-center">
        @if ($product->isInWishlist())
            <form action="{{ route('wishlist.remove', ['product' => $product->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    💔 Retirer de ma wishlist
                </button>
            </form>
        @else
        <form action="{{ route('wishlist.add', ['product' => $product->id]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                ❤️ Ajouter à ma wishlist
            </button>
        </form>
        
        @endif
    </div>
</div>
@else
<div class="row mt-4">
    <div class="col-md-12 text-center">
        <a href="{{ route('login') }}" class="btn btn-outline-secondary">
            Connectez-vous pour ajouter à votre wishlist
        </a>
    </div>
</div>
@endif