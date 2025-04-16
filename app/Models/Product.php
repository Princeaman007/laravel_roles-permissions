<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'short_description',
        'category_id',
        'price',
        'discount_price',
        'stock',
        'image',
        'slug',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category of the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the cart items for the product.
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the sale price (discount price if available, otherwise regular price).
     */
    public function getSalePriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if the product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    /**
     * Calculate the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->is_on_sale) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        
        return 0;
    }

    /**
     * Check if the product is in stock.
     */
    public function getIsInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products on sale.
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('discount_price')
                     ->whereRaw('discount_price < price');
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function isInWishlist()
{
    if (!auth()->check()) return false;

    return $this->wishlistItems()
                ->where('user_id', auth()->id())
                ->exists();
}

public function wishlistItems()
{
    return $this->hasMany(WishlistItem::class);
}

public function getImageUrlAttribute()
{
    if (!$this->image) {
        return 'https://via.placeholder.com/150';
    }

    return asset('storage/' . $this->image);
}

}
