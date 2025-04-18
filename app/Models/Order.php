<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address_id',
        'billing_address_id',
        'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the user that placed the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Get the billing address for the order.
     */
    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Check if the order is pending.
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the order is processing.
     */
    public function getIsProcessingAttribute()
    {
        return $this->status === 'processing';
    }

    /**
     * Check if the order is completed.
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the order is declined.
     */
    public function getIsDeclinedAttribute()
    {
        return $this->status === 'declined';
    }

    /**
     * Check if the order payment is pending.
     */
    public function getIsPaymentPendingAttribute()
    {
        return $this->payment_status === 'pending';
    }

    /**
     * Check if the order is paid.
     */
    public function getIsPaidAttribute()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if the order payment is cancelled.
     */
    public function getIsPaymentCancelledAttribute()
    {
        return $this->payment_status === 'cancelled';
    }

    /**
     * Get the formatted date for the order.
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Format the order status for display.
     */
    public function getFormattedStatusAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'processing' => 'En cours de traitement',
            'completed' => 'Terminée',
            'declined' => 'Annulée'
        ];
        
        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Format the payment status for display.
     */
    public function getFormattedPaymentStatusAttribute()
    {
        $statuses = [
            'pending' => 'En attente',
            'paid' => 'Payé',
            'cancelled' => 'Annulé'
        ];
        
        return $statuses[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Get the total number of items in the order.
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    // Dans App\Models\Order
public function getTotalAmountAttribute()
{
    return $this->total;
}
}