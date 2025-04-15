<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Affiche la liste des adresses de l'utilisateur
     */
    public function index()
    {
        $addresses = Auth::user()->addresses;
        return view('account.addresses.index', compact('addresses'));
    }

    /**
     * Affiche le formulaire de création d'adresse
     */
    public function create()
    {
        return view('account.addresses.create');
    }

    /**
     * Stocke une nouvelle adresse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'nullable|boolean',
        ]);

        $address = new Address($validated);
        $address->user_id = Auth::id();
        
        // Si c'est l'adresse par défaut ou si c'est la première adresse
        if ($request->has('is_default') || Auth::user()->addresses->count() === 0) {
            // Mettre toutes les autres adresses comme non par défaut
            Auth::user()->addresses()->update(['is_default' => false]);
            $address->is_default = true;
        }

        $address->save();

        return redirect()->route('account.addresses')->with('success', 'Adresse ajoutée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'adresse
     */
    public function edit($id)
    {
        $address = Address::findOrFail($id);
        
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        $addressCount = Auth::user()->addresses->count();
        
        return view('account.addresses.edit', compact('address', 'addressCount'));
    }

    /**
     * Met à jour l'adresse
     */
    public function update(Request $request, $id)
    {
        $address = Address::findOrFail($id);
        
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'postal_code' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'name' => 'required|string|max:100',
            'type' => 'required|in:shipping,billing,both',
            'is_default' => 'nullable|boolean',
        ]);
        
        // Si l'adresse est définie comme par défaut
        if ($request->has('is_default')) {
            // Mettre toutes les autres adresses comme non par défaut
            Auth::user()->addresses()->where('id', '!=', $id)->update(['is_default' => false]);
            $validated['is_default'] = true;
        } else {
            // Si cette adresse était par défaut et que l'utilisateur décoche la case
            if ($address->is_default) {
                // Chercher une autre adresse à définir par défaut
                $otherAddress = Auth::user()->addresses()->where('id', '!=', $id)->first();
                if ($otherAddress) {
                    $otherAddress->is_default = true;
                    $otherAddress->save();
                } else {
                    // Si c'est la seule adresse, elle doit rester par défaut
                    $validated['is_default'] = true;
                }
            }
        }
        
        $address->update($validated);
        
        return redirect()->route('account.addresses')->with('success', 'Adresse mise à jour avec succès.');
    }

    /**
     * Supprime l'adresse
     */
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        
        // Vérifier que l'adresse appartient à l'utilisateur connecté
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }
        
        // Vérifier que ce n'est pas la seule adresse
        if (Auth::user()->addresses->count() <= 1) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre seule adresse.');
        }
        
        // Vérifier si l'adresse est utilisée dans des commandes
        $ordersUsingAddress = Order::where('billing_address_id', $id)
                                 ->orWhere('shipping_address_id', $id)
                                 ->exists();
        
        if ($ordersUsingAddress) {
            return redirect()->back()->with('error', 'Cette adresse ne peut pas être supprimée car elle est utilisée dans une ou plusieurs commandes.');
        }
        
        // Si c'était l'adresse par défaut, définir une autre adresse comme par défaut
        if ($address->is_default) {
            $otherAddress = Auth::user()->addresses()->where('id', '!=', $id)->first();
            if ($otherAddress) {
                $otherAddress->is_default = true;
                $otherAddress->save();
            }
        }
        
        $address->delete();
        
        return redirect()->route('account.addresses')->with('success', 'Adresse supprimée avec succès.');
    }
}