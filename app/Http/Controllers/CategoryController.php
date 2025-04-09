<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view-category|create-category|edit-category|delete-category', ['only' => ['index']]);
        $this->middleware('permission:create-category', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-category', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-category', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::with('parent')->orderBy('id', 'DESC')->paginate(10);
        return view('categories.index', compact('categories'));
    }
    
    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = Category::where('parent_id', null)->get();
        return view('categories.create', compact('parentCategories'));
    }
    
    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean'
        ]);
        
        // Générer un slug à partir du nom
        $slug = Str::slug($request->name);
        $count = Category::where('slug', 'LIKE', $slug . '%')->count();
        
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        try {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $slug;
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->is_active = $request->has('is_active') ? 1 : 0;
            $category->save();
            
            return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de la catégorie');
        }
    }
    
    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('parent_id', null)
                                  ->where('id', '!=', $category->id)
                                  ->get();
                                  
        return view('categories.edit', compact('category', 'parentCategories'));
    }
    
    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean'
        ]);
        
        // Vérifier que la catégorie parent n'est pas la catégorie elle-même
        if ($request->parent_id == $category->id) {
            return redirect()->back()->with('error', 'Une catégorie ne peut pas être sa propre catégorie parent');
        }
        
        // Vérifier que la catégorie parent n'est pas un enfant de la catégorie
        if ($request->parent_id) {
            $childIds = $this->getAllChildIds($category->id);
            if (in_array($request->parent_id, $childIds)) {
                return redirect()->back()->with('error', 'Une catégorie ne peut pas avoir une de ses sous-catégories comme parent');
            }
        }
        
        try {
            $category->name = $request->name;
            
            // Ne mettre à jour le slug que si le nom a changé
            if ($category->name != $request->name) {
                $slug = Str::slug($request->name);
                $count = Category::where('slug', 'LIKE', $slug . '%')
                                ->where('id', '!=', $category->id)
                                ->count();
                                
                if ($count > 0) {
                    $slug = $slug . '-' . ($count + 1);
                }
                
                $category->slug = $slug;
            }
            
            $category->description = $request->description;
            $category->parent_id = $request->parent_id;
            $category->is_active = $request->has('is_active') ? 1 : 0;
            $category->save();
            
            return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la mise à jour de la catégorie');
        }
    }
    
    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Vérifier si la catégorie a des produits
        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Cette catégorie ne peut pas être supprimée car elle contient des produits');
        }
        
        try {
            DB::beginTransaction();
            
            // Supprimer les sous-catégories ou les réaffecter
            if ($category->children()->count() > 0) {
                foreach ($category->children as $child) {
                    $child->parent_id = $category->parent_id;
                    $child->save();
                }
            }
            
            $category->delete();
            DB::commit();
            
            return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('categories.index')->with('error', 'Une erreur est survenue lors de la suppression de la catégorie');
        }
    }
    
    /**
     * Get all child category IDs for a given category
     */
    private function getAllChildIds($categoryId)
    {
        $childIds = [];
        $children = Category::where('parent_id', $categoryId)->get();
        
        foreach ($children as $child) {
            $childIds[] = $child->id;
            $childIds = array_merge($childIds, $this->getAllChildIds($child->id));
        }
        
        return $childIds;
    }
}