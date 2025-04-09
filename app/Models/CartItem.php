<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product for the cart item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the subtotal for the cart item.
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Check if the product is still available and in stock.
     */
    public function getIsAvailableAttribute()
    {
        if (!$this->product) {
            return false;
        }
        
        return $this->product->is_active && $this->product->is_in_stock;
    }

    /**
     * Check if the product has enough stock.
     */
    public function getHasEnoughStockAttribute()
    {
        if (!$this->product) {
            return false;
        }
        
        return $this->product->stock >= $this->quantity;
    }

    /**
     * Check if the price has changed since the item was added to the cart.
     */
    public function getPriceChangedAttribute()
    {
        if (!$this->product) {
            return false;
        }
        
        return $this->price != $this->product->sale_price;
    }

    /**
     * Get the current product price.
     */
    public function getCurrentPriceAttribute()
    {
        if (!$this->product) {
            return $this->price;
        }
        
        return $this->product->sale_price;
    }
}