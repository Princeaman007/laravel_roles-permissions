@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="text-success">🎉 Merci pour votre commande !</h1>
        <p class="lead">
            Votre commande <strong>#{{ $order->order_number }}</strong> a bien été enregistrée.
        </p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">

            <!-- Détails de la commande -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Détails de la commande</h5>
                </div>
                <div class="card-body">
                    <p><strong>Numéro :</strong> {{ $order->order_number }}</p>
                    <p><strong>Date :</strong> {{ $order->formatted_date }}</p>
                    <p><strong>Statut :</strong> {{ $order->formatted_status }}</p>
                    <p><strong>Statut du paiement :</strong> {{ $order->formatted_payment_status }}</p>
                    <p><strong>Mode de paiement :</strong> {{ ucfirst($order->payment_method) }}</p>
                    <p><strong>Montant total :</strong> {{ number_format($order->total_amount, 2, ',', ' ') }} €</p>
                    <p><strong>Articles :</strong> {{ $order->total_items }}</p>
                    @if($order->notes)
                        <p><strong>Note :</strong> {{ $order->notes }}</p>
                    @endif
                </div>
            </div>

            <!-- Produits commandés -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Produits commandés</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <strong>{{ $item->product_name }}</strong><br>
                                Quantité : {{ $item->quantity }}
                            </div>
                            <div>
                                {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Adresses -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header"><h5>Adresse de livraison</h5></div>
                        <div class="card-body">
                            @php $shipping = $order->shippingAddress; @endphp
                            @if($shipping)
                                <p>
                                    <strong>{{ $shipping->name }}</strong><br>
                                    {{ $shipping->address_line1 }}<br>
                                    @if($shipping->address_line2)
                                        {{ $shipping->address_line2 }}<br>
                                    @endif
                                    {{ $shipping->postal_code }} {{ $shipping->city }}<br>
                                    {{ $shipping->country }}<br>
                                    Téléphone : {{ $shipping->phone }}
                                </p>
                            @else
                                <p class="text-muted">Non spécifiée</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header"><h5>Adresse de facturation</h5></div>
                        <div class="card-body">
                            @php $billing = $order->billingAddress; @endphp
                            @if($billing)
                                <p>
                                    <strong>{{ $billing->name }}</strong><br>
                                    {{ $billing->address_line1 }}<br>
                                    @if($billing->address_line2)
                                        {{ $billing->address_line2 }}<br>
                                    @endif
                                    {{ $billing->postal_code }} {{ $billing->city }}<br>
                                    {{ $billing->country }}<br>
                                    Téléphone : {{ $billing->phone }}
                                </p>
                            @else
                                <p class="text-muted">Non spécifiée</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Retour à la boutique -->
            <div class="text-center">
                <a href="{{ route('shop.index') }}" class="btn btn-outline-primary">
                    🛍 Retour à la boutique
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
