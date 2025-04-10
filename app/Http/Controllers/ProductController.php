<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Instantiate a new ProductController instance.
     */
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:view-product|create-product|edit-product|delete-product', ['only' => ['index','show']]);
       $this->middleware('permission:create-product', ['only' => ['create','store']]);
       $this->middleware('permission:edit-product', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-product', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::latest()->paginate(3)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
{
    // Préparer les données avec des valeurs par défaut pour les champs manquants
    $data = $request->validated();
    
    // Ajouter des valeurs par défaut si nécessaire
    if (!isset($data['short_description'])) {
        $data['short_description'] = $data['description'] ?? '';
    }
    
    if (!isset($data['slug'])) {
        $data['slug'] = \Str::slug($data['name']);
    }
    
    if (!isset($data['is_active'])) {
        $data['is_active'] = true;
    }
    
    if (!isset($data['category_id'])) {
        // Utiliser une catégorie par défaut ou null
        $data['category_id'] = null;
    }
    
    if (!isset($data['discount_price'])) {
        $data['discount_price'] = null;
    }
    
    // Traiter l'image si elle est présente
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('products', 'public');
    }
    
    // Créer le produit
    Product::create($data);
    
    return redirect()->route('products.index')
            ->withSuccess('New product is added successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('products.show', [
            'product' => $product
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->all());
        return redirect()->back()
                ->withSuccess('Product is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')
                ->withSuccess('Product is deleted successfully.');
    }
}