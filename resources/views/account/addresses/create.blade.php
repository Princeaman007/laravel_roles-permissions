@extends('layouts.app')

@section('title', 'Ajouter une adresse')

@section('content')
<!-- Modal pour ajouter une adresse -->
<div class="modal fade show" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-modal="true" style="display: block;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('account.addresses.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAddressModalLabel">Ajouter une adresse</h5>
                    <a href="{{ url()->previous() }}" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    <!-- Formulaire -->
                    <div class="form-group mb-3">
                        <label for="address_line1">Adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address_line1') is-invalid @enderror" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
                        @error('address_line1') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="address_line2">Complément d'adresse</label>
                        <input type="text" class="form-control @error('address_line2') is-invalid @enderror" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
                        @error('address_line2') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="postal_code">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                                @error('postal_code') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="city">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" required>
                                @error('city') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="state">Région/Province/État <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" required>
                                @error('state') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="country">Pays <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country') }}" required>
                                @error('country') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                        @error('phone') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">Nom de l'adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="type">Type d'adresse <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Adresse de livraison</option>
                            <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Adresse de facturation</option>
                            <option value="both" {{ old('type') == 'both' ? 'selected' : '' }}>Livraison et facturation</option>
                        </select>
                        @error('type') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                    </div>

                    <div class="form-group form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_default">Définir comme adresse par défaut</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection