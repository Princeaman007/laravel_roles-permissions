<?php

namespace App\Http\Controllers; // ← ✅ C’est ça qu’il te manquait !

use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class WishlistController extends Controller
{
    public function add(Product $product)
    {
        WishlistItem::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        return back()->with('success', 'Produit ajouté à votre wishlist.');
    }

    public function remove(Product $product)
    {
        WishlistItem::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->delete();

        return back()->with('success', 'Produit retiré de votre wishlist.');
    }
}
