<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display the checkout page
     */
    public function index()
    {
        // Récupérer le panier
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }
        
        // Récupérer les adresses de l'utilisateur
        $addresses = Address::where('user_id', Auth::id())->get();
        $defaultAddress = $addresses->where('is_default', true)->first();
        
        // Calculer le total
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->price * $item->quantity;
        }
        
        return view('checkout.index', compact('cart', 'addresses', 'defaultAddress', 'total'));
    }
    
    /**
     * Process the checkout
     */
    public function process(Request $request)
    {
        // Valider les données
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:card,paypal',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $cart = Cart::where('user_id', Auth::id())->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }
        
        // Vérifier que les adresses appartiennent bien à l'utilisateur
        $shippingAddress = Address::where('id', $request->shipping_address_id)
                                ->where('user_id', Auth::id())
                                ->first();
                                
        $billingAddress = Address::where('id', $request->billing_address_id)
                               ->where('user_id', Auth::id())
                               ->first();
                               
        if (!$shippingAddress || !$billingAddress) {
            return redirect()->back()->with('error', 'Adresse invalide');
        }
        
        // Calculer le total
        $total = 0;
        foreach ($cart->items as $item) {
            $total += $item->price * $item->quantity;
        }
        
        DB::beginTransaction();
        
        try {
            // Créer la commande
            $order = new Order();
            $order->user_id = Auth::id();
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->status = 'pending';
            $order->total_amount = $total;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'pending';
            $order->shipping_address_id = $shippingAddress->id;
            $order->billing_address_id = $billingAddress->id;
            $order->notes = $request->notes;
            $order->save();
            
            // Ajouter les produits
            foreach ($cart->items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item->product_id;
                $orderItem->product_name = $item->product->name;
                $orderItem->price = $item->price;
                $orderItem->quantity = $item->quantity;
                $orderItem->save();
            }
            
            // Vider le panier
            $cart->items()->delete();
            session()->forget('cart_items_count');
            
            DB::commit();
            
            return redirect()->route('checkout.success', $order)->with('success', 'Commande validée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation de votre commande. Veuillez réessayer.');
        }
    }
    
    /**
     * Display the success page
     */
    public function success(Order $order)
    {
        // Vérifier que la commande appartient bien à l'utilisateur
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }
        
        return view('checkout.success', compact('order'));
    }
}