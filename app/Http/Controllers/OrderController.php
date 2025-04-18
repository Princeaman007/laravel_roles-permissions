<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-order|process-order|cancel-order', ['only' => ['index', 'show']]);
        $this->middleware('permission:process-order', ['only' => ['updateStatus']]);
        $this->middleware('permission:cancel-order', ['only' => ['cancel']]);
    }
    
    /**
     * Display a listing of the orders.
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items']);
        
        // Filtrer par statut
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        // Filtrer par numéro de commande
        if ($request->has('order_number') && !empty($request->order_number)) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        
        // Filtrer par date
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
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
     * Display the specified order.
     */
    /**
 * Display the specified order.
 */
public function show(Order $order, Request $request)
{
    $order->load(['user', 'items.product', 'shippingAddress', 'billingAddress']);

    
    if ($request->has('invoice')) {
        return view('orders.invoice', compact('order'));
    }
    
    // Fallback si pas d'adresse dans la commande
    $defaultShippingAddress = null;

    if (!$order->shippingAddress && auth()->check()) {
        $defaultShippingAddress = auth()->user()
            ->addresses()
            ->where('is_default', true)
            ->where('type', 'shipping') // optionnel
            ->first();
    }

    return view('orders.show', compact('order', 'defaultShippingAddress'));
}
    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,declined'
        ]);
        
        $oldStatus = $order->status;
        $order->status = $request->status;
        
        // Si la commande est marquée comme terminée, mettre à jour le statut de paiement
        if ($request->status === 'completed' && $order->payment_status === 'pending') {
            $order->payment_status = 'paid';
        }
        
        // Si la commande est annulée, mettre à jour le statut de paiement
        if ($request->status === 'declined' && $order->payment_status === 'pending') {
            $order->payment_status = 'cancelled';
        }
        
        $order->save();
        
        // Vous pourriez envoyer un e-mail à l'utilisateur ici pour l'informer du changement de statut
        
        return redirect()->route('orders.show', $order)->with('success', 'Le statut de la commande a été mis à jour avec succès.');
    }
    
    /**
     * Cancel the specified order.
     */
    public function cancel(Order $order)
    {
        if ($order->status === 'completed') {
            return redirect()->route('orders.show', $order)->with('error', 'Impossible d\'annuler une commande déjà livrée.');
        }
        
        $order->status = 'declined';
        $order->payment_status = 'cancelled';
        $order->save();
        
        // Vous pourriez envoyer un e-mail à l'utilisateur ici pour l'informer de l'annulation
        
        return redirect()->route('orders.show', $order)->with('success', 'La commande a été annulée avec succès.');
    }
    
    /**
     * Generate invoice for the specified order.
     */
    public function generateInvoice(Order $order)
    {
        $order->load(['user', 'items.product', 'shippingAddress', 'billingAddress']);
        
        // Ici, vous pourriez générer un PDF pour la facture en utilisant une bibliothèque comme DomPDF
        // Exemple simplifié :
        /*
        $pdf = PDF::loadView('invoices.template', compact('order'));
        return $pdf->download('facture-'.$order->order_number.'.pdf');
        */
        
        // Pour l'instant, on renvoie simplement vers une vue de facture
        return view('orders.invoice', compact('order'));
    }
}