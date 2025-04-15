<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // ✅ Marquer les notifications non lues comme lues
        Auth::user()->unreadNotifications->markAsRead();

        // Récupérer les paramètres de filtrage et de tri
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $sort = $request->input('sort', 'newest'); // Par défaut, trier par produits les plus récents

        // Requête de base pour récupérer les produits
        $query = Product::query();

        // Appliquer les filtres de prix si spécifiés
        if ($priceMin !== null) {
            $query->where('price', '>=', $priceMin);
        }
        
        if ($priceMax !== null) {
            $query->where('price', '<=', $priceMax);
        }

        // Appliquer le tri
        switch ($sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        // Récupérer les produits avec pagination
        $products = $query->where('is_active', true)->paginate(9);
        
        // Conserver les paramètres de requête pour la pagination
        $products->appends($request->query());
        
        // Récupérer toutes les catégories
        $categories = Category::all();

        return view('home', compact('products', 'categories'));
    }
}