@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">✏️ Modifier la catégorie</h1>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div class="mb-3">
                    <label class="form-label">Nom de la catégorie</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control">{{ old('description', $category->description) }}</textarea>
                </div>

                {{-- Parent --}}
                <div class="mb-3">
                    <label class="form-label">Catégorie parente</label>
                    <select name="parent_id" class="form-select">
                        <option value="">Aucune</option>
                        @foreach ($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ $category->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Statut --}}
                <div class="form-check form-switch mb-4">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input"
                           {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="form-check-label">Activer la catégorie</label>
                </div>

                {{-- Bouton --}}
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Enregistrer les modifications
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
