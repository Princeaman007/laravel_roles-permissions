@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Mes adresses</span>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAddressModal">
                        Ajouter une adresse
                    </button>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($addresses->isEmpty())
                        <div class="alert alert-info">
                            Vous n'avez pas encore ajouté d'adresse.
                        </div>
                    @else
                        <div class="row">
                            @foreach($addresses as $address)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 {{ $address->is_default ? 'border-primary' : '' }}">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span>
                                                @if($address->type == 'shipping')
                                                    <i class="fas fa-shipping-fast mr-1"></i> Adresse de livraison
                                                @elseif($address->type == 'billing')
                                                    <i class="fas fa-file-invoice mr-1"></i> Adresse de facturation
                                                @else
                                                    <i class="fas fa-home mr-1"></i> Adresse de livraison et facturation
                                                @endif
                                                
                                                @if($address->is_default)
                                                    <span class="badge badge-primary ml-2">Par défaut</span>
                                                @endif
                                            </span>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="addressActions{{ $address->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    Actions
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="addressActions{{ $address->id }}">
                                                    <button class="dropdown-item edit-address-btn" 
                                                            data-toggle="modal" 
                                                            data-target="#editAddressModal" 
                                                            data-address="{{ json_encode($address) }}">
                                                        <i class="fas fa-edit mr-1"></i> Modifier
                                                    </button>
                                                    <form action="{{ route('account.addresses.delete', $address) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse?')">
                                                            <i class="fas fa-trash mr-1"></i> Supprimer
                                                        </button>
                                                    </form>
                                                    @if(!$address->is_default)
                                                        <form action="{{ route('account.addresses.update', $address) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="address_line1" value="{{ $address->address_line1 }}">
                                                            <input type="hidden" name="address_line2" value="{{ $address->address_line2 }}">
                                                            <input type="hidden" name="city" value="{{ $address->city }}">
                                                            <input type="hidden" name="state" value="{{ $address->state }}">
                                                            <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
                                                            <input type="hidden" name="country" value="{{ $address->country }}">
                                                            <input type="hidden" name="phone" value="{{ $address->phone }}">
                                                            <input type="hidden" name="type" value="{{ $address->type }}">
                                                            <input type="hidden" name="is_default" value="1">
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="fas fa-star mr-1"></i> Définir par défaut
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-1">{{ $address->address_line1 }}</p>
                                            @if($address->address_line2)
                                                <p class="mb-1">{{ $address->address_line2 }}</p>
                                            @endif
                                            <p class="mb-1">{{ $address->postal_code }} {{ $address->city }}</p>
                                            <p class="mb-1">{{ $address->state }}</p>
                                            <p class="mb-1">{{ $address->country }}</p>
                                            <p class="mb-0"><i class="fas fa-phone mr-1"></i> {{ $address->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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

<!-- Modal pour modifier une adresse -->
<div class="modal fade" id="editAddressModal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="editAddressForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editAddressModalLabel">Modifier l'adresse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_address_line1">Adresse <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('address_line1') is-invalid @enderror" id="edit_address_line1" name="address_line1" required>
                        @error('address_line1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit_address_line2">Complément d'adresse</label>
                        <input type="text" class="form-control @error('address_line2') is-invalid @enderror" id="edit_address_line2" name="address_line2">
                        @error('address_line2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_postal_code">Code postal <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" id="edit_postal_code" name="postal_code" required>
                                @error('postal_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_city">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="edit_city" name="city" required>
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
                                <label for="edit_state">Région/Province/État <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="edit_state" name="state" required>
                                @error('state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_country">Pays <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="edit_country" name="country" required>
                                @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Téléphone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="edit_phone" name="phone" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="edit_type">Type d'adresse <span class="text-danger">*</span></label>
                        <select class="form-control @error('type') is-invalid @enderror" id="edit_type" name="type" required>
                            <option value="shipping">Adresse de livraison</option>
                            <option value="billing">Adresse de facturation</option>
                            <option value="both">Adresse de livraison et facturation</option>
                        </select>
                        @error('type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_default" name="is_default" value="1">
                        <label class="form-check-label" for="edit_is_default">Définir comme adresse par défaut</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Remplir les champs du formulaire de modification avec les données de l'adresse
        const editButtons = document.querySelectorAll('.edit-address-btn');
        
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const addressData = JSON.parse(this.dataset.address);
                const form = document.getElementById('editAddressForm');
                
                // Définir l'action du formulaire
                form.action = `/account/addresses/${addressData.id}`;
                
                // Remplir les champs du formulaire
                document.getElementById('edit_address_line1').value = addressData.address_line1;
                document.getElementById('edit_address_line2').value = addressData.address_line2 || '';
                document.getElementById('edit_postal_code').value = addressData.postal_code;
                document.getElementById('edit_city').value = addressData.city;
                document.getElementById('edit_state').value = addressData.state;
                document.getElementById('edit_country').value = addressData.country;
                document.getElementById('edit_phone').value = addressData.phone;
                
                // Sélectionner le type d'adresse
                const typeSelect = document.getElementById('edit_type');
                for (let i = 0; i < typeSelect.options.length; i++) {
                    if (typeSelect.options[i].value === addressData.type) {
                        typeSelect.options[i].selected = true;
                        break;
                    }
                }
                
                // Cocher/décocher la case adresse par défaut
                document.getElementById('edit_is_default').checked = addressData.is_default;
            });
        });
    });
</script>
@endsection