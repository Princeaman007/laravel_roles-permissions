<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\User;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\OrderConfirmationMail;
use App\Notifications\NewOrderNotification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche la page de checkout
     */
    public function index()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $addresses = Address::where('user_id', Auth::id())->get();
        $defaultAddress = $addresses->where('is_default', true)->first();

        $taxRate = config('tva.rate', 0.21);
        $discount = 0;
        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $shipping = $subtotal >= 100 ? 0 : 5.00;
        $tax = $subtotal * $taxRate;
        $total = $subtotal + $tax + $shipping - $discount;

        $totals = compact('subtotal', 'tax', 'shipping', 'discount', 'total');

        return view('checkout.index', compact('cart', 'addresses', 'defaultAddress', 'totals', 'taxRate'));
    }

    /**
     * Traite la commande
     */
    public function process(Request $request)
    {
        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:card,paypal',
            'notes' => 'nullable|string|max:500'
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $shippingAddress = Address::where('id', $request->shipping_address_id)
            ->where('user_id', Auth::id())->first();

        $billingAddress = Address::where('id', $request->billing_address_id)
            ->where('user_id', Auth::id())->first();

        if (!$shippingAddress || !$billingAddress) {
            return redirect()->back()->with('error', 'Adresse invalide');
        }

        $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $taxRate = config('tva.rate', 0.21);
        $tax = $subtotal * $taxRate;
        $shippingCost = $subtotal >= 100 ? 0 : 5.00;
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
            $order = new Order();
            $order->user_id = Auth::id();
            $order->order_number = 'ORD-' . strtoupper(Str::random(10));
            $order->status = 'pending';
            $order->payment_method = $request->payment_method;
            $order->payment_status = 'pending';
            $order->shipping_address_id = $shippingAddress->id;
            $order->billing_address_id = $billingAddress->id;
            $order->notes = $request->notes;

            $order->subtotal = $subtotal;
            $order->tax = $tax;
            $order->shipping_cost = $shippingCost;
            $order->discount = $discount;
            $order->total = $total;
            $order->save();

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);

                // 🔁 Mettre à jour le stock
                $product = $item->product;
                if ($product && $product->stock >= $item->quantity) {
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }

            $cart->items()->delete();
            session()->forget('cart_items_count');

            DB::commit();

            // ✅ Envoi mail confirmation
            $user = Auth::user();
            if ($user && $user->email) {
                Mail::to($user->email)->send(new OrderConfirmationMail($order));
            }

            // 🔔 Notification admin
            $admins = User::whereHas('roles', fn($q) => $q->whereIn('name', ['Admin', 'Super Admin']))->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
            }

            return redirect()->route('checkout.success', $order)->with('success', 'Commande validée avec succès');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur commande : ' . $e->getMessage());
            return redirect()->back()->with('error', 'Une erreur est survenue. Détail : ' . $e->getMessage());
        }
    }

    /**
     * Page de succès
     */
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home')->with('error', 'Accès non autorisé');
        }

        return view('checkout.success', compact('order'));
    }
}
