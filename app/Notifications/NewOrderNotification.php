<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // tu peux ajouter 'mail' si tu veux l’envoyer aussi par email
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Nouvelle commande reçue',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'user_name' => $this->order->user->name,
            'total' => $this->order->total_amount,
        ];
    }
}
