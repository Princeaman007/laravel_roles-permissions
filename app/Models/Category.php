<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all active products in this category.
     */
    public function activeProducts()
    {
        return $this->products()->where('is_active', true);
    }

    /**
     * Check if category has active child categories.
     */
    public function hasActiveChildren()
    {
        return $this->children()->where('is_active', true)->exists();
    }

    /**
     * Check if category has products.
     */
    public function hasProducts()
    {
        return $this->products()->exists();
    }

    /**
     * Get all products including those in child categories.
     */
    public function getAllProducts()
    {
        $ids = $this->getAllChildrenIds();
        $ids[] = $this->id;
        
        return Product::whereIn('category_id', $ids);
    }

    /**
     * Get all child category IDs.
     */
    protected function getAllChildrenIds()
    {
        $ids = [];
        
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        
        return $ids;
    }
}