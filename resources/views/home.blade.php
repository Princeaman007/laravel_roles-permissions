@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar avec les commandes administratives -->
        <div class="col-md-3 mb-4">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Administration</h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        @canany(['create-role', 'edit-role', 'delete-role'])
                            <a class="btn btn-outline-primary" href="{{ route('roles.index') }}">
                                <i class="bi bi-person-fill-gear"></i> Gérer les rôles
                            </a>
                        @endcanany
                        
                        @canany(['create-user', 'edit-user', 'delete-user'])
                            <a class="btn btn-outline-success" href="{{ route('users.index') }}">
                                <i class="bi bi-people"></i> Gérer les utilisateurs
                            </a>
                        @endcanany
                        
                        @canany(['create-product', 'edit-product', 'delete-product'])
                            <a class="btn btn-outline-warning" href="{{ route('products.index') }}">
                                <i class="bi bi-bag"></i> Gérer les produits
                            </a>
                        @endcanany
                        
                        <a class="btn btn-outline-secondary" href="{{ route('account.addresses') }}">
                            <i class="bi bi-geo-alt"></i> Mes adresses
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Placeholder pour les filtres de produits qui seront ajoutés via AJAX -->
            <div id="product-filters"></div>
        </div>
        
        <!-- Zone principale - Les produits -->
        <div class="col-md-9">
            <div id="productsList" class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nos produits</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Trier par
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_asc">Prix croissant</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="price_desc">Prix décroissant</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="newest">Plus récents</a></li>
                            <li><a class="dropdown-item sort-option" href="#" data-sort="popularity">Popularité</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Indicateur de chargement -->
                    <div class="text-center p-5" id="loading-indicator">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-2">Chargement des produits...</p>
                    </div>
                    
                    <!-- La liste des produits sera injectée ici -->
                    <div id="products-container" class="row row-cols-1 row-cols-md-3 g-4" style="display: none;">
                    </div>
                    
                    <!-- Pagination -->
                    <div id="pagination-container" class="mt-4"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadProducts();
        
        // Gestion du tri
        document.querySelectorAll('.sort-option').forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                loadProducts(this.dataset.sort);
            });
        });
        
        // Fonction pour charger les produits via AJAX
        function loadProducts(sort = null) {
            document.getElementById('loading-indicator').style.display = 'block';
            document.getElementById('products-container').style.display = 'none';
            
            let url = "{{ route('products.index') }}";
            if (sort) {
                url += `?sort=${sort}`;
            }
            
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    
                    // Extraire les filtres si disponibles
                    const filters = doc.querySelector('.col-md-3');
                    if (filters) {
                        document.getElementById('product-filters').innerHTML = filters.innerHTML;
                    }
                    
                    // Extraire les produits
                    const productsContainer = doc.querySelector('.row.row-cols-1.row-cols-md-3');
                    if (productsContainer) {
                        document.getElementById('products-container').innerHTML = productsContainer.innerHTML;
                    } else {
                        document.getElementById('products-container').innerHTML = '<div class="col-12"><div class="alert alert-info">Aucun produit disponible.</div></div>';
                    }
                    
                    // Extraire la pagination
                    const pagination = doc.querySelector('.pagination');
                    if (pagination) {
                        document.getElementById('pagination-container').innerHTML = pagination.outerHTML;
                    } else {
                        document.getElementById('pagination-container').innerHTML = '';
                    }
                    
                    document.getElementById('loading-indicator').style.display = 'none';
                    document.getElementById('products-container').style.display = 'flex';
                    
                    // Ajuster les liens de pagination pour qu'ils fonctionnent via AJAX
                    document.querySelectorAll('#pagination-container a').forEach(link => {
                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            fetch(this.href)
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    
                                    const productsContainer = doc.querySelector('.row.row-cols-1.row-cols-md-3');
                                    if (productsContainer) {
                                        document.getElementById('products-container').innerHTML = productsContainer.innerHTML;
                                    }
                                    
                                    const pagination = doc.querySelector('.pagination');
                                    if (pagination) {
                                        document.getElementById('pagination-container').innerHTML = pagination.outerHTML;
                                        
                                        // Réappliquer les événements aux liens de pagination
                                        document.querySelectorAll('#pagination-container a').forEach(link => {
                                            link.addEventListener('click', arguments.callee);
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Erreur de pagination:', error);
                                });
                        });
                    });
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des produits:', error);
                    document.getElementById('loading-indicator').style.display = 'none';
                    document.getElementById('products-container').style.display = 'block';
                    document.getElementById('products-container').innerHTML = 
                        `<div class="col-12">
                            <div class="alert alert-danger">
                                Une erreur est survenue lors du chargement des produits.
                                <a href="{{ route('products.index') }}" class="btn btn-sm btn-primary mt-2">
                                    Voir tous les produits
                                </a>
                            </div>
                        </div>`;
                });
        }
    });
</script>
@endsection