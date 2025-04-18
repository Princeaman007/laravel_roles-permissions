@component('mail::message')
# Bonjour {{ $user->name }},

Le statut de votre commande **#{{ $order->order_number }}** a été mis à jour.

---

### 🆕 Nouveau statut :  
**{{ ucfirst($order->status) }}**

@component('mail::button', ['url' => route('account.orders.show', $order->id)])
Voir ma commande
@endcomponent

@if($order->status === 'completed')
Merci pour votre achat ! 🎉  
Votre commande est terminée, et nous espérons vous revoir très bientôt !
@elseif($order->status === 'processing')
Votre commande est en cours de traitement. Vous recevrez une mise à jour dès son expédition. 🚚
@elseif($order->status === 'declined')
Malheureusement, votre commande a été annulée. Si vous avez des questions, contactez notre support.
@else
Votre commande est actuellement en statut : {{ ucfirst($order->status) }}.
@endif

Merci pour votre confiance,  
L’équipe **{{ config('app.name') }}**
@endcomponent
