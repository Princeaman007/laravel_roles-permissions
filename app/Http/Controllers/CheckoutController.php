<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmationMail;
use App\Models\User;
use App\Notifications\NewOrderNotification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the checkout page
     */
    public function index()
{
    $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

    if (!$cart || $cart->items->isEmpty()) {
        return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
    }

    $addresses = Address::where('user_id', Auth::id())->get();
    $defaultAddress = $addresses->where('is_default', true)->first();

    // 💰 Calculs des montants
    $taxRate = config('tva.rate', 0.21); // Configurable
    $shipping = 5.00; // Peut être dynamique plus tard
    $discount = 0;

    $subtotal = $cart->items->sum(function ($item) {
        return $item->price * $item->quantity;
    });

    $tax = $subtotal * $taxRate;
    $total = $subtotal + $tax + $shipping - $discount;

    $totals = compact('subtotal', 'tax', 'shipping', 'discount', 'total');

    return view('checkout.index', compact('cart', 'addresses', 'defaultAddress', 'totals', 'taxRate'));
}


    /**
     * Process the checkout
     */
    public function process(Request $request)
{
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

    $shippingAddress = Address::where('id', $request->shipping_address_id)
        ->where('user_id', Auth::id())
        ->first();

    $billingAddress = Address::where('id', $request->billing_address_id)
        ->where('user_id', Auth::id())
        ->first();

    if (!$shippingAddress || !$billingAddress) {
        return redirect()->back()->with('error', 'Adresse invalide');
    }

    // ✅ Calculs financiers
    $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
    $taxRate = config('tva.rate', 0.21);
    $tax = $subtotal * $taxRate;
    $shippingCost = 5.00; // tu peux aussi le calculer dynamiquement plus tard
    $discount = 0;
    $total = $subtotal + $tax + $shippingCost - $discount;
    

    foreach ($cart->items as $item) {
        if ($item->quantity > $item->product->stock) {
            return redirect()->route('cart.index')
                ->with('error', "Le stock du produit « {$item->product->name} » est insuffisant.");
        }
    }
    
    DB::beginTransaction();

    try {
        // ✅ Création de la commande
        $order = new Order();
        $order->user_id = Auth::id();
        $order->order_number = 'ORD-' . strtoupper(Str::random(10));
        $order->status = 'pending';
        $order->payment_method = $request->payment_method;
        $order->payment_status = 'pending';
        $order->shipping_address_id = $shippingAddress->id;
        $order->billing_address_id = $billingAddress->id;
        $order->notes = $request->notes;

        // ✅ Données financières
        $order->subtotal = $subtotal;
        $order->tax = $tax;
        $order->shipping_cost = $shippingCost;
        $order->discount = $discount;
        $order->total = $total;

        $order->save();

        // ✅ Enregistrement des produits commandés
        foreach ($cart->items as $item) {
            // Créer l'élément de commande
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->product_id;
            $orderItem->product_name = $item->product->name;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->quantity;
            $orderItem->save();
        
            
            $product = $item->product;
        
            if ($product && $product->stock >= $item->quantity) {
                $product->stock -= $item->quantity;
                $product->save();
            }
        }
        

        // ✅ Nettoyage du panier
        $cart->items()->delete();
        session()->forget('cart_items_count');

        DB::commit();

        // ✅ Envoi d'e-mail au client
        Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order));

        // ✅ Notifications admin
        $admins = User::whereHas('roles', function ($q) {
            $q->whereIn('name', ['Admin', 'Super Admin']);
        })->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }

        return redirect()->route('checkout.success', $order)->with('success', 'Commande validée avec succès');

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Erreur commande : ' . $e->getMessage());
        return redirect()->back()->with('error', 'Une erreur est survenue lors de la validation de votre commande. Veuillez réessayer. Détail : ' . $e->getMessage());
    }
}


    /**
     * Display the success page
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        return view('checkout.success', compact('order'));
    }
}
