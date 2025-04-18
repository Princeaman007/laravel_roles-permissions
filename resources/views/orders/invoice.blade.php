@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Facture #{{ $order->order_number }}</h5>
            <div>
                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-secondary me-2">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
                <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body p-4">
            <!-- Entête de facture -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted">Informations client</h6>
                    <div class="mb-3">
                        <strong>{{ $order->user->name }}</strong><br>
                        {{ $order->user->email }}<br>
                        @if($order->billingAddress)
                            {{ $order->billingAddress->address_line1 }}<br>
                            @if($order->billingAddress->address_line2)
                                {{ $order->billingAddress->address_line2 }}<br>
                            @endif
                            {{ $order->billingAddress->postal_code }} {{ $order->billingAddress->city }}<br>
                            {{ $order->billingAddress->country }}
                        @endif
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="text-muted">Informations commande</h6>
                    <div class="mb-3">
                        <strong>Commande:</strong> #{{ $order->order_number }}<br>
                        <strong>Date:</strong> {{ $order->formattedDate }}<br>
                        <strong>Statut:</strong> {{ $order->formattedStatus }}<br>
                        <strong>Paiement:</strong> {{ $order->formattedPaymentStatus }}
                    </div>
                </div>
            </div>
            
            <!-- Articles -->
            <div class="table-responsive mb-4">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Produit</th>
                            <th class="text-center">Quantité</th>
                            <th class="text-end">Prix unitaire</th>
                            <th class="text-end">Montant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div>{{ $item->product->name ?? 'Produit indisponible' }}</div>
                                @if($item->options)
                                <small class="text-muted">
                                    @foreach(json_decode($item->options, true) ?? [] as $key => $value)
                                        <div>{{ ucfirst($key) }}: {{ $value }}</div>
                                    @endforeach
                                </small>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->unit_price, 2, ',', ' ') }} €</td>
                            <td class="text-end">{{ number_format($item->unit_price * $item->quantity, 2, ',', ' ') }} €</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Sous-total</strong></td>
                            <td class="text-end">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Livraison</strong></td>
                            <td class="text-end">0,00 €</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total</strong></td>
                            <td class="text-end fw-bold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Notes et conditions -->
            <div class="row">
                <div class="col-md-8">
                    <h6>Notes</h6>
                    <p class="text-muted small">{{ $order->notes ?? 'Aucune note spécifique pour cette commande.' }}</p>
                    
                    <h6 class="mt-3">Conditions</h6>
                    <p class="text-muted small">
                        Merci pour votre commande. Cette facture est valable comme preuve d'achat.
                        Les délais de livraison sont donnés à titre indicatif.
                    </p>
                </div>
                <div class="col-md-4">
                    <div class="text-center mt-4 pt-2">
                        <p class="text-muted small mb-1">Facture générée le</p>
                        <p>{{ now()->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css" media="print">
    @page {
        size: A4;
        margin: 10mm;
    }
    body {
        background-color: #fff !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    .card-header {
        background-color: #fff !important;
    }
    .btn, .navbar, footer {
        display: none !important;
    }
</style>
@endsection