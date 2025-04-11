@extends('layouts.app')

@section('title', 'Détails de la commande')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3>Détails de la commande #{{ $order->order_number }}</h3>
                </div>
                <div class="card-body">
                    <p><strong>Date :</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p><strong>Statut :</strong> {{ $order->status }}</p>
                    
                    <h5>Articles :</h5>
                    <ul class="list-group">
                        @foreach($order->items as $item)
                            <li class="list-group-item">
                                <strong>{{ $item->product_name }}</strong><br>
                                Quantité : {{ $item->quantity }}<br>
                                Prix : {{ number_format($item->price, 2) }} €
                            </li>
                        @endforeach
                    </ul>

                    <p><strong>Total :</strong> {{ number_format($order->total_amount, 2) }} €</p>

                    <h5>Adresse de livraison :</h5>
                    <p>
                        {{ $order->shippingAddress->address_line1 }}<br>
                        {{ $order->shippingAddress->address_line2 }}<br>
                        {{ $order->shippingAddress->city }} {{ $order->shippingAddress->postal_code }}<br>
                        {{ $order->shippingAddress->country }}<br>
                        <strong>Téléphone :</strong> {{ $order->shippingAddress->phone }}
                    </p>

                    <a href="{{ route('account.orders') }}" class="btn btn-primary">Retour à mes commandes</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
