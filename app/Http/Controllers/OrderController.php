<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderStatusUpdated;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-order|process-order|cancel-order', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-order', ['only' => ['updateStatus']]);
        $this->middleware('permission:cancel-order', ['only' => ['cancel']]);
    }

    /**
     * Affiche la liste des commandes avec filtres dynamiques
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tri
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $allowedSortFields = ['id', 'order_number', 'created_at', 'status', 'total_amount'];

        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('orders.index', compact('orders'));
    }

    /**
     * Affiche les détails d'une commande
     */
    public function show(Order $order, Request $request)
    {
        $order->load(['user', 'items.product', 'shippingAddress', 'billingAddress']);

        if ($request->has('invoice')) {
            return view('orders.invoice', compact('order'));
        }

        $defaultShippingAddress = null;
        if (!$order->shippingAddress && auth()->check()) {
            $defaultShippingAddress = auth()->user()
                ->addresses()
                ->where('is_default', true)
                ->where('type', 'shipping')
                ->first();
        }

        return view('orders.show', compact('order', 'defaultShippingAddress'));
    }

    /**
     * Met à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,declined'
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;

        // Mise à jour du statut de paiement
        if ($order->payment_status === 'pending') {
            if ($order->status === 'completed') {
                $order->payment_status = 'paid';
            } elseif ($order->status === 'declined') {
                $order->payment_status = 'cancelled';
            }
        }

        $order->save();

        // ✅ Notification par mail
        if ($order->user && $order->user->email) {
            $order->user->notify(new OrderStatusUpdated($order));
        }

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }

    /**
     * Annule une commande
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', $order)->with('error', 'Impossible d\'annuler une commande déjà livrée.');
        }

        $order->status = 'declined';
        $order->payment_status = 'cancelled';
        $order->save();

        // ✅ Notification si souhaitée
        if ($order->user && $order->user->email) {
            $order->user->notify(new OrderStatusUpdated($order));
        }

        return redirect()->route('orders.show', $order)->with('success', 'La commande a été annulée avec succès.');
    }

    /**
     * Génère une facture
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress', 'billingAddress']);
        return view('orders.invoice', compact('order'));
    }
}
