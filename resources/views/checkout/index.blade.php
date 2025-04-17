@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- ✅ Formulaire à gauche -->
        <div class="col-md-8">
            <h1 class="mb-4">Finaliser votre commande</h1>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                {{-- Adresse de livraison --}}
                <div class="card mb-4">
                    <div class="card-header"><h5>Adresse de livraison</h5></div>
                    <div class="card-body">
                        @if($addresses->isEmpty())
                            <div class="alert alert-warning">
                                Vous n'avez pas encore d'adresse enregistrée.
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    Ajouter une nouvelle adresse
                                </button>
                            </div>
                        @else
                            <div class="mb-3">
                                @foreach($addresses as $address)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="shipping_address_id"
                                            id="shipping_address_{{ $address->id }}" value="{{ $address->id }}"
                                            {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping_address_{{ $address->id }}">
                                            <strong>{{ $address->name }}</strong><br>
                                            {{ $address->address_line1 }}<br>
                                            @if($address->address_line2) {{ $address->address_line2 }}<br> @endif
                                            {{ $address->postal_code }} {{ $address->city }}<br>
                                            {{ $address->country }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                Ajouter une nouvelle adresse
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Adresse de facturation --}}
                <div class="card mb-4">
                    <div class="card-header"><h5>Adresse de facturation</h5></div>
                    <div class="card-body">
                        @if($addresses->isEmpty())
                            <div class="alert alert-warning">
                                Vous n'avez pas encore d'adresse enregistrée.
                            </div>
                        @else
                            <div class="mb-3">
                                @foreach($addresses as $address)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="billing_address_id"
                                            id="billing_address_{{ $address->id }}" value="{{ $address->id }}"
                                            {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billing_address_{{ $address->id }}">
                                            <strong>{{ $address->name }}</strong><br>
                                            {{ $address->address_line1 }}<br>
                                            @if($address->address_line2) {{ $address->address_line2 }}<br> @endif
                                            {{ $address->postal_code }} {{ $address->city }}<br>
                                            {{ $address->country }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                Ajouter une nouvelle adresse
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Paiement --}}
                <div class="card mb-4">
                    <div class="card-header"><h5>Mode de paiement</h5></div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                            <label class="form-check-label" for="payment_card">Carte bancaire</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                            <label class="form-check-label" for="payment_paypal">PayPal</label>
                        </div>
                    </div>
                </div>

                {{-- Notes --}}
                <div class="card mb-4">
                    <div class="card-header"><h5>Notes</h5></div>
                    <div class="card-body">
                        <textarea class="form-control" name="notes" rows="3" placeholder="Instructions spéciales pour la livraison..."></textarea>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">✅ Confirmer la commande</button>
                </div>
            </form>
        </div>

        <!-- ✅ Récapitulatif à droite -->
        <div class="col-md-4 mt-4 mt-md-0">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5>Récapitulatif de commande</h5>
                </div>
                <div class="card-body">
                    @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div><strong>{{ $item->quantity }}x</strong> {{ $item->product->name }}</div>
                            <div>{{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €</div>
                        </div>
                    @endforeach

                    <hr>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Sous-total (HT)</span>
                        <span>{{ number_format($totals['subtotal'], 2, ',', ' ') }} €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>TVA ({{ $taxRate * 100 }}%)</span>
                        <span>{{ number_format($totals['tax'], 2, ',', ' ') }} €</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Livraison</span>
                        <span>
                            @if($totals['shipping'] == 0)
                                <span class="badge bg-success">Livraison offerte</span>
                            @else
                                {{ number_format($totals['shipping'], 2, ',', ' ') }} €
                            @endif
                        </span>
                    </div>

                    @if($totals['subtotal'] < 100)
                        <p class="text-muted small mt-2">
                            📦 Livraison gratuite dès 100 € — il vous manque
                            <strong>{{ number_format(100 - $totals['subtotal'], 2, ',', ' ') }} €</strong>.
                        </p>
                    @endif

                    <hr>

                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total TTC</span>
                        <span>{{ number_format($totals['total'], 2, ',', ' ') }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ✅ Modal pour ajout d'adresse -->
@includeIf('account.addresses._modal_create')

@endsection
