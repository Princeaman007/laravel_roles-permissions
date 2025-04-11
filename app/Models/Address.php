<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'is_default',
        'type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping orders for the address.
     */
    public function shippingOrders()
    {
        return $this->hasMany(Order::class, 'shipping_address_id');
    }

    /**
     * Get the billing orders for the address.
     */
    public function billingOrders()
    {
        return $this->hasMany(Order::class, 'billing_address_id');
    }

    /**
     * Get the full name of the address.
     */
    public function getFullAddressAttribute()
    {
        $address = $this->address_line1;
        
        if (!empty($this->address_line2)) {
            $address .= ', ' . $this->address_line2;
        }
        
        $address .= ', ' . $this->city . ', ' . $this->state . ' ' . $this->postal_code;
        $address .= ', ' . $this->country;
        
        return $address;
    }

    /**
     * Check if the address is used for shipping.
     */
    public function getIsShippingAttribute()
    {
        return in_array($this->type, ['shipping', 'both']);
    }

    /**
     * Check if the address is used for billing.
     */
    public function getIsBillingAttribute()
    {
        return in_array($this->type, ['billing', 'both']);
    }

    /**
     * Format the type for display.
     */
    public function getFormattedTypeAttribute()
    {
        $types = [
            'shipping' => 'Livraison',
            'billing' => 'Facturation',
            'both' => 'Livraison et facturation'
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    /**
     * Check if the address is used in any order.
     */
    public function getIsUsedInOrdersAttribute()
    {
        return $this->shippingOrders()->exists() || $this->billingOrders()->exists();
    }
}