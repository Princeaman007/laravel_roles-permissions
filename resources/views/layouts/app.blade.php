
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel E-commerce') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- Styles et Scripts via Vite -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                Laravel E-commerce
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop.index') }}">Boutique</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" role="button"
                           data-bs-toggle="dropdown">
                            Catégories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarCategories">
                            @foreach(App\Models\Category::where('is_active', true)->orderBy('name')->get() as $category)

                                <li>
                                    <a class="dropdown-item" href="{{ route('shop.category', $category->slug) }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @endforeach
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('shop.index') }}">Toutes les catégories</a>
                            </li>
                        </ul>
                    </li>

                    @guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('contact.form') }}">Contact</a>
    </li>
@else
    @if (!auth()->user()->hasAnyRole(['admin', 'super-admin']))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contact.form') }}">Contact</a>
        </li>
    @endif
@endguest

                    
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    <!-- Panier -->
                    <li class="nav-item">
                        <a href="{{ route('cart.index') }}" class="nav-link">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge bg-danger rounded-pill">
                                {{ Session::get('cart_items_count', 0) }}
                            </span>
                        </a>
                    </li>

                    <!-- Auth -->
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @endif
                    @else
                        <!-- Admin Links -->
                        @canany(['create-role', 'edit-role', 'delete-role'])
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Gestion des rôles</a></li>
                        @endcanany
                        @canany(['create-user', 'edit-user', 'delete-user'])
                            <li><a class="nav-link" href="{{ route('users.index') }}">Gestion des utilisateurs</a></li>
                        @endcanany
                        @canany(['create-product', 'edit-product', 'delete-product'])
                            <li><a class="nav-link" href="{{ route('products.index') }}">Gestion des produits</a></li>
                        @endcanany
                        @canany(['create-category', 'edit-category', 'delete-category'])
                            <li><a class="nav-link" href="{{ route('categories.index') }}">Gestion des catégories</a></li>
                        @endcanany
                        @canany(['view-order', 'process-order', 'cancel-order'])
                            <li><a class="nav-link" href="{{ route('orders.index') }}">Gestion des commandes</a></li>
                        @endcanany

                        <!-- Dropdown utilisateur -->
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="{{ route('account.index') }}">
                                    <i class="bi bi-person"></i> Mon compte
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('account.orders') }}">
                                    <i class="bi bi-box-seam"></i> Mes commandes
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('account.addresses') }}">
                                    <i class="bi bi-geo-alt"></i> Mes adresses
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                    
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center mt-3">
                <div class="col-md-12">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success text-center" role="alert">
                            {{ $message }}
                        </div>
                    @endif
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger text-center" role="alert">
                            {{ $message }}
                        </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-light py-4 mt-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>À propos</h5>
                    <p>Notre boutique en ligne vous propose une large gamme de produits de qualité.</p>
                </div>
                <div class="col-md-4">
                    <h5>Liens utiles</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('shop.index') }}">Boutique</a></li>
                        <li><a href="#">Conditions générales de vente</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="{{ route('contact.form') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt"></i> Avenue de lille 4 A/52 4020 Liege</li>
                        <li><i class="bi bi-envelope"></i> tfeetude@gmail.com</li>
                        <li><i class="bi bi-telephone"></i> +32 467 620 878</li>
                    </ul>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; {{ date('Y') }} Laravel E-commerce. Tous droits réservés.</p>
                </div>
            </div>
        </div>
    </footer>
</div>

<!-- Script d'initialisation des dropdowns -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Vérifier si Bootstrap est disponible
        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            console.log('✅ Dropdown Bootstrap actif');
            
            // Initialiser manuellement tous les dropdowns
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                try {
                    var dropdown = new bootstrap.Dropdown(element);
                } catch (e) {
                    console.error('Erreur lors de l\'initialisation du dropdown:', element, e);
                }
            });
            
            // Ajouter des écouteurs d'événements pour les dropdowns (optionnel pour débogage)
            document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                element.addEventListener('show.bs.dropdown', function () {
                    console.log('Dropdown en cours d\'ouverture:', this);
                });
                
                element.addEventListener('shown.bs.dropdown', function () {
                    console.log('Dropdown ouvert:', this);
                });
            });
        } else {
            console.warn('❌ Dropdown Bootstrap non trouvé');
            
            // Tenter de charger Bootstrap depuis le CDN si non disponible
            if (typeof bootstrap === 'undefined') {
                console.log('Tentative de chargement de Bootstrap...');
                var script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js';
                script.onload = function() {
                    console.log('Bootstrap chargé avec succès, initialisation des dropdowns...');
                    document.querySelectorAll('.dropdown-toggle').forEach(function(element) {
                        new bootstrap.Dropdown(element);
                    });
                };
                document.body.appendChild(script);
            }
        }
    });
</script>

</body>
</html>
`