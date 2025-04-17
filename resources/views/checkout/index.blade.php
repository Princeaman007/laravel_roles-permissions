@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">Finaliser votre commande</h1>

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf
                
                <!-- Adresse de livraison -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Adresse de livraison</h5>
                    </div>
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
                                            <strong>{{ $address->name ?? '' }}</strong><br>
                                            {{ $address->address_line1 }}<br>
                                            @if($address->address_line2)
                                                {{ $address->address_line2 }}<br>
                                            @endif
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

                <!-- Adresse de facturation -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Adresse de facturation</h5>
                    </div>
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
                                        <input class="form-check-input" type="radio" name="billing_address_id" 
                                            id="billing_address_{{ $address->id }}" value="{{ $address->id }}"
                                            {{ $defaultAddress && $defaultAddress->id == $address->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billing_address_{{ $address->id }}">
                                            <strong>{{ $address->name ?? '' }}</strong><br>
                                            {{ $address->address_line1 }}<br>
                                            @if($address->address_line2)
                                                {{ $address->address_line2 }}<br>
                                            @endif
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

                <!-- Mode de paiement -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Mode de paiement</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" checked>
                            <label class="form-check-label" for="payment_card">
                                Carte bancaire
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal">
                            <label class="form-check-label" for="payment_paypal">
                                PayPal
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Notes</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <textarea class="form-control" name="notes" id="notes" rows="3" placeholder="Instructions spéciales pour la livraison..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-lg">Confirmer la commande</button>
                </div>
            </form>
        </div>

        <!-- Récapitulatif de commande -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Récapitulatif de commande</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        @if(isset($cart) && $cart->items)
                            @foreach($cart->items as $item)
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <span class="fw-bold">{{ $item->quantity }}x</span> {{ $item->product->name }}
                                    </div>
                                    <div>
                                        {{ number_format($item->price * $item->quantity, 2, ',', ' ') }} €
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
        
                    <hr>
        
                    <div class="d-flex justify-content-between mb-2">
                        <div>Sous-total</div>
                        <div>{{ number_format($totals['subtotal'], 2, ',', ' ') }} €</div>
                    </div>
        
                    <div class="d-flex justify-content-between mb-2">
                        <div>TVA ({{ $taxRate * 100 }}%)</div>
                        <div>{{ number_format($totals['tax'], 2, ',', ' ') }} €</div>
                    </div>
        
                    <div class="d-flex justify-content-between mb-2">
                        <div>Livraison</div>
                        <div>{{ number_format($totals['shipping'], 2, ',', ' ') }} €</div>
                    </div>
        
                    @if($totals['discount'] > 0)
                        <div class="d-flex justify-content-between mb-2 text-danger">
                            <div>Remise</div>
                            <div>-{{ number_format($totals['discount'], 2, ',', ' ') }} €</div>
                        </div>
                    @endif
        
                    <hr>
        
                    <div class="d-flex justify-content-between fw-bold">
                        <div>Total TTC</div>
                        <div>{{ number_format($totals['total'], 2, ',', ' ') }} €</div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<!-- Modal pour ajouter une adresse -->
<div class="modal fade" id="addAddressModal" tabindex="-1" role="dialog" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Ajouter une adresse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>



                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="address_line1">Adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address_line1') is-invalid @enderror" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
                        @error('address_line1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address_line2">Complément d'adresse</label>
                        <input type="text" class="form-control @error('address_line2') is-invalid @enderror" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
                        @error('address_line2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="postal_code">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                @error('postal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state">Région/Province/État <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Pays <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="type">Type d'adresse <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Adresse de livraison</option>
                            <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Adresse de facturation</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Adresse de livraison et facturation</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <!-- Champ pour nom de l'adresse, qui est peut-être manquant -->
                    <div class="form-group">
                        <label for="name">Nom de l'adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">Définir comme adresse par défaut</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Fonction pour fermer la modal (si besoin, sinon Bootstrap le fait tout seul)
    function closeModal(modalElement) {
        var modal = modalElement || document.querySelector('.modal.show');
        if (!modal) return;

        var modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }

        var backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) backdrop.parentNode.removeChild(backdrop);
    }

    // Initialiser les gestionnaires de fermeture manuelle (juste au cas où)
    document.addEventListener('DOMContentLoaded', function () {
        var closeButtons = document.querySelectorAll('[data-bs-dismiss="modal"]');
        closeButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                closeModal(this.closest('.modal'));
            });
        });
    });
</script>
@endsection
