@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Modifier un produit</h5>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="productForm">
                        @csrf
                        @method("PUT")
                        
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="mb-3 row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start fw-bold">Nom du produit <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $product->name }}" required autofocus>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Entrez un nom unique et descriptif pour votre produit.</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3 row">
                                    <label for="slug" class="col-md-4 col-form-label text-md-end text-start fw-bold">Slug (URL) <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ $product->slug }}" required>
                                        @error('slug')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <small class="form-text text-muted">Sera utilisé dans l'URL du produit (généré automatiquement).</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3 row">
                                    <label for="category_id" class="col-md-4 col-form-label text-md-end text-start fw-bold">Catégorie <span class="text-danger">*</span></label>
                                    <div class="col-md-8">
                                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                            <option value="">Sélectionnez une catégorie</option>
                                            <optgroup label="Électronique">
                                                <option value="1" {{ $product->category_id == 1 ? 'selected' : '' }}>Électronique (Général)</option>
                                                <option value="2" {{ $product->category_id == 2 ? 'selected' : '' }}>Smartphone</option>
                                                <option value="3" {{ $product->category_id == 3 ? 'selected' : '' }}>Ordinateur</option>
                                            </optgroup>
                                        </select>
                                        @error('category_id')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-bold">Image du produit</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <small class="form-text text-muted">Format recommandé: JPG/PNG, max 2MB.</small>
                                </div>
                                <div class="text-center mt-3">
                                    <div class="image-preview-container border rounded p-2 d-flex justify-content-center align-items-center" style="height: 200px; background-color: #f8f9fa;">
                                        <img id="imagePreview" src="{{ $product->image_url ?? '#' }}" alt="Image du produit" class="img-fluid" style="max-height: 190px; max-width: 100%; {{ !$product->image_url ? 'display: none;' : '' }}">
                                        <div id="uploadPlaceholder" class="text-center text-muted" {{ $product->image_url ? 'style="display: none;"' : '' }}>
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                            <p class="mt-2 mb-0">Aperçu de l'image</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="short_description" class="col-md-4 col-form-label text-md-end text-start fw-bold">Description courte <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" value="{{ $product->short_description }}" maxlength="255" required>
                                @error('short_description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Résumé bref du produit (max 255 caractères).</small>
                            </div>
                        </div>
                        
                        <div class="mb-3 row">
                            <label for="description" class="col-md-4 col-form-label text-md-end text-start fw-bold">Description complète <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ $product->description }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">Description détaillée du produit, supports HTML basique.</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <label for="price" class="col-md-6 col-form-label text-md-end text-start fw-bold">Prix normal <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ $product->price }}" required>
                                        </div>
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <label for="discount_price" class="col-md-6 col-form-label text-md-end text-start fw-bold">Prix réduit</label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number" step="0.01" min="0" class="form-control @error('discount_price') is-invalid @enderror" id="discount_price" name="discount_price" value="{{ $product->discount_price }}">
                                        </div>
                                        @error('discount_price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div id="discountFeedback" class="text-danger small mt-1"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 row">
                                    <label for="stock" class="col-md-6 col-form-label text-md-end text-start fw-bold">Stock <span class="text-danger">*</span></label>
                                    <div class="col-md-6">
                                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ $product->stock }}" required>
                                        @error('stock')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4 row">
                            <div class="col-md-4 text-md-end text-start">
                                <label class="form-check-label fw-bold" for="is_active">Statut du produit</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Produit actif</label>
                                </div>
                                <small class="form-text text-muted">Désactivez pour masquer temporairement ce produit sur le site.</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between border-top pt-4 mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Mettre à jour le produit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productForm');
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const priceInput = document.getElementById('price');
        const discountPriceInput = document.getElementById('discount_price');
        const discountFeedback = document.getElementById('discountFeedback');
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        
        // Générer automatiquement le slug à partir du nom
        nameInput.addEventListener('input', function() {
            const slug = this.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Supprime les caractères spéciaux
                .replace(/\s+/g, '-')     // Remplace les espaces par des tirets
                .replace(/-+/g, '-')      // Évite les tirets multiples
                .replace(/^-+|-+$/g, ''); // Supprime les tirets au début et à la fin
            
            slugInput.value = slug;
        });
        
        // Validation du prix réduit
        function validateDiscountPrice() {
            const price = parseFloat(priceInput.value);
            const discountPrice = parseFloat(discountPriceInput.value);
            
            if (discountPrice && price && discountPrice >= price) {
                discountFeedback.textContent = 'Le prix réduit doit être inférieur au prix normal';
                return false;
            } else {
                discountFeedback.textContent = '';
                return true;
            }
        }
        
        priceInput.addEventListener('input', validateDiscountPrice);
        discountPriceInput.addEventListener('input', validateDiscountPrice);
        
        // Prévisualisation de l'image
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    uploadPlaceholder.style.display = 'none';
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
        
        // Validation du formulaire
        form.addEventListener('submit', function(event) {
            if (!validateDiscountPrice()) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
</script>
@endpush
@endsection