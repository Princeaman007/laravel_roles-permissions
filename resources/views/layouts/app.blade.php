<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
    use Illuminate\Support\Facades\Session;
    @endphp
    

    <title>{{ config('app.name', 'Laravel E-commerce') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    Laravel E-commerce
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('shop.index') }}">Boutique</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarCategories" role="button" data-bs-toggle="dropdown">
                                Catégories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarCategories">
                                @foreach(App\Models\Category::where('is_active', true)->take(6)->get() as $category)
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
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                      <!-- Panier -->
                        <li class="nav-item">
                            <a href="{{ route('cart.index') }}" class="nav-link">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge bg-danger rounded-pill">{{ Session::get('cart_items_count', 0) }}</span>
                            </a>
                        </li>
                        
                        <!-- Authentication Links -->
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
                            <!-- Panneau d'administration -->
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
                            
                            <!-- Compte utilisateur -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('account.index') }}">
                                        <i class="bi bi-person"></i> Mon compte
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.orders') }}">
                                        <i class="bi bi-box-seam"></i> Mes commandes
                                    </a>
                                    <a class="dropdown-item" href="{{ route('account.addresses') }}">
                                        <i class="bi bi-geo-alt"></i> Mes adresses
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
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
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5>Contact</h5>
                        <ul class="list-unstyled">
                            <li><i class="bi bi-geo-alt"></i> 123 Rue du Commerce, 75000 Paris</li>
                            <li><i class="bi bi-envelope"></i> contact@ecommerce.com</li>
                            <li><i class="bi bi-telephone"></i> +33 1 23 45 67 89</li>
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
</body>
</html>