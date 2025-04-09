<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'session_id'
    ];

    /**
     * Get the user that owns the cart.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of the cart.
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    /**
     * Get the total quantity of items in the cart.
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Check if the cart has items.
     */
    public function getHasItemsAttribute()
    {
        return $this->items->isNotEmpty();
    }

    /**
     * Add a product to the cart.
     */
    public function addProduct(Product $product, int $quantity = 1)
    {
        $item = $this->items()->where('product_id', $product->id)->first();
        
        if ($item) {
            // Update quantity if product already in cart
            $item->quantity += $quantity;
            $item->save();
            return $item;
        } else {
            // Add new item to cart
            return $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->sale_price
            ]);
        }
    }

    /**
     * Update an item quantity in the cart.
     */
    public function updateQuantity(int $productId, int $quantity)
    {
        $item = $this->items()->where('product_id', $productId)->first();
        
        if ($item) {
            if ($quantity <= 0) {
                // Remove item if quantity is zero or negative
                $item->delete();
                return null;
            } else {
                // Update quantity
                $item->quantity = $quantity;
                $item->save();
                return $item;
            }
        }
        
        return null;
    }

    /**
     * Remove a product from the cart.
     */
    public function removeProduct(int $productId)
    {
        return $this->items()->where('product_id', $productId)->delete();
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        return $this->items()->delete();
    }
}