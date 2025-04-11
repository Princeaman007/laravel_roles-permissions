<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /**
     * Afficher le tableau de bord du compte
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les 3 dernières commandes
        $recentOrders = Order::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->take(3)
                            ->get();
        
        // Récupérer l'adresse de livraison par défaut
        $defaultShippingAddress = Address::where('user_id', $user->id)
                                      ->where('is_default_shipping', true)
                                      ->first();
        
        // Récupérer les 4 derniers éléments de la liste de souhaits
        $wishlistItems = WishlistItem::where('user_id', $user->id)
                                  ->with('product')
                                  ->orderBy('created_at', 'desc')
                                  ->take(4)
                                  ->get();
        
        return view('account.index', compact('recentOrders', 'defaultShippingAddress', 'wishlistItems'));
    }
    
    /**
     * Afficher la page du profil utilisateur
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }
    
    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Vérifier le mot de passe actuel si un nouveau mot de passe est fourni
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.'])->withInput();
            }
        }
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        return redirect()->route('account.profile')->with('success', 'Votre profil a été mis à jour avec succès.');
    }
    
    /**
     * Afficher la liste des commandes
     */
    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('account.orders.index', compact('orders'));
    }
    
    /**
     * Afficher les détails d'une commande spécifique
     */
    public function showOrder(Order $order)
    {
        // Vérifier que la commande appartient à l'utilisateur connecté
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('account.orders.show', compact('order'));
    }
    
    /**
     * Afficher la liste des adresses
     */
    public function addresses()
{
    $addresses = Address::where('user_id', Auth::id())->get();
    return view('account.addresses.index', compact('addresses'));
}

    
    /**
     * Afficher le formulaire de création d'une adresse
     */
    public function createAddress()
    {
        return view('account.addresses.create');
    }
    
    /**
     * Enregistrer une nouvelle adresse
     */
    public function storeAddress(Request $request)
{
    $request->validate([
        'full_name' => 'required|string|max:255',
        'address_line1' => 'required|string|max:255',
        'address_line2' => 'nullable|string|max:255',
        'city' => 'required|string|max:100',
        'postal_code' => 'required|string|max:20',
        'country' => 'required|string|max:100',
        'phone' => 'required|string|max:20',
        'name' => 'required|string|max:255', // Assurez-vous que le nom de l'adresse soit valide
        'type' => 'required|in:shipping,billing,both', // Assurez-vous que le type soit valide
        'is_default' => 'nullable|boolean', // Validation pour le checkbox is_default
    ]);
    
    // Créer une nouvelle instance d'adresse
    $address = new Address($request->all());
    $address->user_id = Auth::id();
    
    // Si c'est la première adresse, la définir comme adresse par défaut
    $addressCount = Address::where('user_id', Auth::id())->count();
    
    if ($addressCount === 0 || $request->has('is_default')) {
        // Si c'est la première adresse ou que l'utilisateur veut définir cette adresse comme par défaut
        $address->is_default_shipping = true;
        $address->is_default_billing = true;
    } else {
        // Si une autre adresse est définie comme adresse par défaut, mettre à jour les autres adresses
        if ($request->has('is_default_shipping') && $request->is_default_shipping) {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_shipping' => false]);
        }
        
        if ($request->has('is_default_billing') && $request->is_default_billing) {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_billing' => false]);
        }
    }

    // Sauvegarder la nouvelle adresse
    $address->save();
    
    return redirect()->route('account.addresses')->with('success', 'Adresse ajoutée avec succès.');
}

    
    /**
     * Afficher le formulaire d'édition d'une adresse
     */
    public function editAddress(Address $address)
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('account.addresses.edit', compact('address'));
    }
    
    /**
     * Mettre à jour une adresse
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'is_default_shipping' => 'boolean',
            'is_default_billing' => 'boolean',
        ]);
        
        // Si la nouvelle adresse est définie comme adresse par défaut, mettre à jour les autres adresses
        if ($request->has('is_default_shipping') && $request->is_default_shipping && !$address->is_default_shipping) {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_shipping' => false]);
        }
        
        if ($request->has('is_default_billing') && $request->is_default_billing && !$address->is_default_billing) {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_billing' => false]);
        }
        
        $address->update($request->all());
        
        return redirect()->route('account.addresses')->with('success', 'Adresse mise à jour avec succès.');
    }
    
    /**
     * Supprimer une adresse
     */
    public function destroyAddress(Address $address)
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Vérifier s'il s'agit de la seule adresse
        $addressCount = Address::where('user_id', Auth::id())->count();
        
        if ($addressCount === 1) {
            return redirect()->route('account.addresses')->with('error', 'Vous ne pouvez pas supprimer votre seule adresse.');
        }
        
        // Si c'est l'adresse par défaut, définir une autre adresse comme adresse par défaut
        if ($address->is_default_shipping || $address->is_default_billing) {
            $newDefault = Address::where('user_id', Auth::id())
                               ->where('id', '!=', $address->id)
                               ->first();
            
            if ($address->is_default_shipping) {
                $newDefault->is_default_shipping = true;
            }
            
            if ($address->is_default_billing) {
                $newDefault->is_default_billing = true;
            }
            
            $newDefault->save();
        }
        
        $address->delete();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse supprimée avec succès.');
    }
    
    /**
     * Définir une adresse comme adresse par défaut
     */
    public function setDefaultAddress(Request $request, Address $address)
    {
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $type = $request->input('type', 'shipping');
        
        if ($type === 'shipping') {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_shipping' => false]);
            
            $address->is_default_shipping = true;
        } else {
            Address::where('user_id', Auth::id())
                  ->update(['is_default_billing' => false]);
            
            $address->is_default_billing = true;
        }
        
        $address->save();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse par défaut mise à jour.');
    }
    
    /**
     * Afficher la liste de souhaits
     */
    public function wishlist()
    {
        $wishlistItems = WishlistItem::where('user_id', Auth::id())
                                  ->with('product')
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(12);
        
        return view('account.wishlist', compact('wishlistItems'));
    }
    
    /**
     * Ajouter un produit à la liste de souhaits
     */
    public function addToWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        // Vérifier si le produit est déjà dans la liste de souhaits
        $exists = WishlistItem::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->exists();
        
        if (!$exists) {
            WishlistItem::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ]);
            
            return redirect()->back()->with('success', 'Produit ajouté à votre liste de souhaits.');
        }
        
        return redirect()->back()->with('info', 'Ce produit est déjà dans votre liste de souhaits.');
    }
    
    /**
     * Supprimer un produit de la liste de souhaits
     */
    public function removeFromWishlist(WishlistItem $wishlistItem)
    {
        // Vérifier que l'élément appartient à l'utilisateur connecté
        if ($wishlistItem->user_id !== Auth::id()) {
            abort(403);
        }
        
        $wishlistItem->delete();
        
        return redirect()->back()->with('success', 'Produit retiré de votre liste de souhaits.');
    }
}