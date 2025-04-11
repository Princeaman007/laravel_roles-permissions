@component('mail::message')
# Merci pour votre commande 🎉

Bonjour {{ $order->user->name }},

Votre commande **#{{ $order->order_number }}** a bien été enregistrée le **{{ $order->formatted_date }}**.

---

## 🧾 Détails de la commande

- **Statut :** {{ $order->formatted_status }}
- **Paiement :** {{ ucfirst($order->payment_method) }} — {{ $order->formatted_payment_status }}
- **Montant total :** {{ number_format($order->total_amount, 2, ',', ' ') }} €
- **Articles :** {{ $order->total_items }}

@if($order->notes)
- **Note :** {{ $order->notes }}
@endif

---

## 📦 Produits commandés

@foreach($order->items as $item)
- {{ $item->quantity }}x {{ $item->product_name }} — {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
@endforeach

---

## 🚚 Adresse de livraison

@if($order->shippingAddress)
{{ $order->shippingAddress->name }}  
{{ $order->shippingAddress->address_line1 }}  
@if($order->shippingAddress->address_line2)
{{ $order->shippingAddress->address_line2 }}  
@endif
{{ $order->shippingAddress->postal_code }} {{ $order->shippingAddress->city }}  
{{ $order->shippingAddress->country }}  
📞 {{ $order->shippingAddress->phone }}
@endif

---

## 🧾 Adresse de facturation

@if($order->billingAddress)
{{ $order->billingAddress->name }}  
{{ $order->billingAddress->address_line1 }}  
@if($order->billingAddress->address_line2)
{{ $order->billingAddress->address_line2 }}  
@endif
{{ $order->billingAddress->postal_code }} {{ $order->billingAddress->city }}  
{{ $order->billingAddress->country }}  
📞 {{ $order->billingAddress->phone }}
@endif

---

@component('mail::button', ['url' => route('shop.index')])
Continuer vos achats
@endcomponent

Merci pour votre confiance 🙏  
L’équipe {{ config('app.name') }}
@endcomponent
