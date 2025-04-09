<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display the shop homepage with products
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);
        
        // Filtres (prix, catégorie, etc.)
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Tri
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        
        return view('shop.index', compact('products', 'categories'));
    }
    
    /**
     * Display products from a specific category
     */
    public function category(Category $category)
    {
        $products = Product::where('is_active', true)
                          ->where('category_id', $category->id)
                          ->paginate(12);
        
        $categories = Category::where('is_active', true)->get();
        
        return view('shop.category', compact('products', 'categories', 'category'));
    }
    
    /**
     * Display a single product detail page
     */
    public function product(Product $product)
    {
        if (!$product->is_active) {
            return redirect()->route('shop.index')->with('error', 'Ce produit n\'est pas disponible');
        }
        
        // Produits similaires de la même catégorie
        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->limit(4)
                                 ->get();
                                 
        return view('shop.product', compact('product', 'relatedProducts'));
    }
}