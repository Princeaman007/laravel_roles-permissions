<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart
     */
    public function index()
    {
        $cart = $this->getCart();
        $cartItems = $cart ? $cart->items : collect([]);
    
        // Si le panier est vide, on retourne des totaux à 0
        if ($cartItems->isEmpty()) {
            return view('cart.index', [
                'cart' => $cart,
                'cartItems' => $cartItems,
                'totals' => [
                    'subtotal' => 0,
                    'tax' => 0,
                    'shipping_cost' => 0,
                    'discount' => 0,
                    'total' => 0
                ],
                'taxRate' => 0.21
            ]);
        }
    
        // 💰 Calcul des totaux
        $taxRate = 0.21; // TVA 21% (Belgique)
        $discount = 0;
    
        $subtotal = $cartItems->sum(fn($item) => $item->price * $item->quantity);
        $tax = $subtotal * $taxRate;
    
        // 🚚 Livraison gratuite à partir de 100 €
        $shipping = $subtotal >= 100 ? 0 : 5.00;
    
        $total = $subtotal + $tax + $shipping - $discount;
    
        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'totals' => [
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'discount' => $discount,
                'total' => $total
            ],
            'taxRate' => $taxRate
        ]);
    }
    

    
    /**
     * Add a product to the cart
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
    
        $product = Product::findOrFail($request->product_id);
    
        if (!$product->is_active) {
            return redirect()->back()->with('error', 'Ce produit n\'est pas disponible.');
        }
    
        if ($product->stock === 0) {
            return redirect()->back()->with('error', 'Ce produit est actuellement en rupture de stock.');
        }
    
        $cart = $this->getCart(true); // true = créer si n'existe pas
    
        // Vérifier si le produit est déjà dans le panier
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product->id)
                            ->first();
    
        $existingQty = $cartItem ? $cartItem->quantity : 0;
        $requestedQty = $request->quantity;
        $newTotalQty = $existingQty + $requestedQty;
    
        if ($newTotalQty > $product->stock) {
            return redirect()->back()->with('error', "Vous avez demandé $requestedQty unités (déjà $existingQty dans le panier), mais il ne reste que {$product->stock} en stock.");
        }
    
        if ($cartItem) {
            // Mettre à jour la quantité
            $cartItem->quantity = $newTotalQty;
            $cartItem->save();
        } else {
            // Ajouter nouveau produit
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $requestedQty,
                'price' => $product->discount_price ?? $product->price
            ]);
        }
    
        $this->updateCartItemsCount($cart);
    
        return redirect()->back()->with('success', 'Produit ajouté au panier.');
    }
    
    
    /**
     * Update cart items quantity
     */
    public function update(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:cart_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);
        
        foreach ($request->items as $item) {
            $cartItem = CartItem::findOrFail($item['id']);
            $cartItem->update(['quantity' => $item['quantity']]);
        }
        
        $cart = $this->getCart();
        $this->updateCartItemsCount($cart);
        
        return redirect()->back()->with('success', 'Panier mis à jour');
    }
    
    /**
     * Remove an item from the cart
     */
    public function remove(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:cart_items,id'
        ]);
        
        CartItem::destroy($request->item_id);
        
        $cart = $this->getCart();
        $this->updateCartItemsCount($cart);
        
        return redirect()->back()->with('success', 'Produit retiré du panier');
    }
    
    /**
     * Clear the cart (remove all items)
     */
    public function clear()
    {
        $cart = $this->getCart();
        
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
            $this->updateCartItemsCount($cart);
        }
        
        return redirect()->back()->with('success', 'Panier vidé');
    }
    
    /**
     * Get or create a cart
     */
    private function getCart($createIfNotExists = false)
    {
        $cart = null;
        
        if (Auth::check()) {
            // Utilisateur connecté
            $cart = Cart::where('user_id', Auth::id())->first();
            
            // Si l'utilisateur a un panier de session, le fusionner avec son panier utilisateur
            if (Session::has('cart_id')) {
                $sessionCart = Cart::find(Session::get('cart_id'));
                
                if ($sessionCart) {
                    if (!$cart) {
                        // Transférer le panier de session à l'utilisateur
                        $sessionCart->update(['user_id' => Auth::id()]);
                        $cart = $sessionCart;
                    } else {
                        // Fusionner les éléments du panier de session dans le panier utilisateur
                        foreach ($sessionCart->items as $item) {
                            $existingItem = CartItem::where('cart_id', $cart->id)
                                                  ->where('product_id', $item->product_id)
                                                  ->first();
                            
                            if ($existingItem) {
                                $existingItem->quantity += $item->quantity;
                                $existingItem->save();
                            } else {
                                $item->cart_id = $cart->id;
                                $item->save();
                            }
                        }
                        
                        // Supprimer le panier de session
                        $sessionCart->delete();
                    }
                    
                    Session::forget('cart_id');
                }
            }
        } else {
            // Utilisateur non connecté - utiliser un panier de session
            if (Session::has('cart_id')) {
                $cart = Cart::find(Session::get('cart_id'));
            }
        }
        
        // Créer un panier si nécessaire
        if ($createIfNotExists && !$cart) {
            $cart = new Cart();
            
            if (Auth::check()) {
                $cart->user_id = Auth::id();
            } else {
                $cart->session_id = Session::getId();
            }
            
            $cart->save();
            
            if (!Auth::check()) {
                Session::put('cart_id', $cart->id);
            }
        }
        
        return $cart;
    }
    
    /**
     * Update the cart items count in session
     */
    private function updateCartItemsCount($cart)
    {
        if ($cart) {
            $count = CartItem::where('cart_id', $cart->id)->sum('quantity');
            Session::put('cart_items_count', $count);
        } else {
            Session::put('cart_items_count', 0);
        }
    }
}