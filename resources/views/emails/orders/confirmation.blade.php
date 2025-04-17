@component('mail::message')
# Merci pour votre commande 🎉

**Commande #{{ $order->order_number }}**  
Date : {{ $order->created_at->format('d/m/Y H:i') }}

---

**Résumé :**

@foreach($order->items as $item)
- {{ $item->quantity }} × {{ $item->product_name }} = {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
@endforeach

**Sous-total :** {{ number_format($order->subtotal, 2, ',', ' ') }} €  
**TVA :** {{ number_format($order->tax, 2, ',', ' ') }} €  
**Livraison :** {{ $order->shipping_cost > 0 ? number_format($order->shipping_cost, 2, ',', ' ') . ' €' : 'Offerte' }}  
@if($order->discount > 0)
**Remise :** -{{ number_format($order->discount, 2, ',', ' ') }} €
@endif

### Total TTC : {{ number_format($order->total, 2, ',', ' ') }} €

---

@component('mail::button', ['url' => route('shop.index')])
Retour à la boutique
@endcomponent

Merci pour votre confiance 🙏  
L’équipe {{ config('app.name') }}
@endcomponent
