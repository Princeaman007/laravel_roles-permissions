<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
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
     * Display the customer dashboard
     */
    public function index()
    {
        $user = Auth::user();
        // Récupérer les 3 dernières commandes
        $recentOrders = Order::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                            
        // Récupérer le nombre d'adresses de l'utilisateur
        $addressCount = Address::where('user_id', $user->id)->count();
        
        return view('account.index', compact('user', 'recentOrders', 'addressCount'));
    }

    /**
     * Display all customer orders
     */
    public function orders()
    {
        $user = Auth::user();
        
        // Récupérer toutes les commandes de l'utilisateur
        $orders = Order::where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('account.orders.index', compact('orders'));
    }

    /**
     * Display a specific order details
     */
    public function showOrder(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('account.orders')->with('error', 'Accès non autorisé');
        }
        
        return view('orders.show', compact('order'));
    }
    
    /**
     * Display all customer addresses
     */
    public function addresses()
    {
        // Récupérer les adresses de l'utilisateur connecté
        $addresses = Address::where('user_id', Auth::id())->get();
        
        return view('account.addresses', compact('addresses'));
    }

    /**
     * Store a new address
     */
    public function storeAddress(Request $request)
    {
        // Validation des données d'adresse
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
            'type' => 'required|in:shipping,billing,both'
        ]);

        // Si l'adresse est définie par défaut, réinitialiser les autres adresses
        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        // Créer la nouvelle adresse
        $address = new Address();
        $address->user_id = Auth::id();
        $address->address_line1 = $request->address_line1;
        $address->address_line2 = $request->address_line2;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->phone = $request->phone;
        $address->is_default = $request->is_default ? true : false;
        $address->type = $request->type;
        $address->save();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse ajoutée avec succès');
    }

    /**
     * Update an address
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Vérifier que l'adresse appartient bien à l'utilisateur
        if ($address->user_id !== Auth::id()) {
            return redirect()->route('account.addresses')->with('error', 'Accès non autorisé');
        }

        // Validation des données d'adresse
        $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default' => 'nullable|boolean',
            'type' => 'required|in:shipping,billing,both'
        ]);

        // Si l'adresse est définie par défaut, réinitialiser les autres adresses
        if ($request->is_default) {
            Address::where('user_id', Auth::id())->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        // Mettre à jour l'adresse
        $address->address_line1 = $request->address_line1;
        $address->address_line2 = $request->address_line2;
        $address->city = $request->city;
        $address->state = $request->state;
        $address->postal_code = $request->postal_code;
        $address->country = $request->country;
        $address->phone = $request->phone;
        $address->is_default = $request->is_default ? true : false;
        $address->type = $request->type;
        $address->save();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse mise à jour avec succès');
    }

    /**
     * Delete an address
     */
    public function deleteAddress(Address $address)
    {
        // Vérifier que l'adresse appartient bien à l'utilisateur
        if ($address->user_id !== Auth::id()) {
            return redirect()->route('account.addresses')->with('error', 'Accès non autorisé');
        }

        // Vérifier si l'adresse est utilisée dans des commandes
        $ordersCount = Order::where(function($query) use ($address) {
            $query->where('shipping_address_id', $address->id)
                  ->orWhere('billing_address_id', $address->id);
        })->count();

        if ($ordersCount > 0) {
            return redirect()->route('account.addresses')->with('error', 'Cette adresse ne peut pas être supprimée car elle est utilisée dans une ou plusieurs commandes');
        }

        // Si c'est l'adresse par défaut, définir une autre adresse comme défaut
        if ($address->is_default) {
            $newDefault = Address::where('user_id', Auth::id())
                                  ->where('id', '!=', $address->id)
                                  ->first();
                                  
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->save();
            }
        }

        $address->delete();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse supprimée avec succès');
    }
}
