<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\WishlistController;

Auth::routes();

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
]);

// Routes publiques (boutique)
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{category:slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{product:slug}', [ShopController::class, 'product'])->name('shop.product');
Route::get('/produit/{slug}', [ProductController::class, 'show'])->name('product.show');

// Routes du panier
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::group(['middleware' => 'auth'], function () {
    // Routes de checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Nouvelles routes pour le compte utilisateur avec AccountController
    Route::prefix('account')->name('account.')->group(function () {
        // Tableau de bord
        Route::get('/', [AccountController::class, 'index'])->name('index');
        
        // Commandes
        Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
        
        // Profil utilisateur
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');

        // Liste de souhaits
        Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
        // Route::post('/wishlist/add', [AccountController::class, 'addToWishlist'])->name('wishlist.add');
        // Route::delete('/wishlist/{wishlistItem}', [AccountController::class, 'removeFromWishlist'])->name('wishlist.remove');
        
        // Routes pour les adresses avec AddressController
        Route::get('/addresses', [AddressController::class, 'index'])->name('addresses');
        Route::get('/addresses/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/addresses/{address}/edit', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
    });
}); 

// Routes admin (protégées)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:Admin|Super Admin|Shop Manager']], function () {
    Route::resources([
        'categories' => CategoryController::class,
        'orders' => OrderController::class,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::post('/wishlist/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Routes pour la gestion des statuts de commande
Route::patch('orders/{order}/update-status', [App\Http\Controllers\OrderController::class, 'updateStatus'])
    ->name('orders.updateStatus')
    ->middleware('permission:process-order');

Route::patch('orders/{order}/cancel', [App\Http\Controllers\OrderController::class, 'cancel'])
    ->name('orders.cancel')
    ->middleware('permission:cancel-order');

   
Route::get('orders/{order}/invoice', [App\Http\Controllers\OrderController::class, 'generateInvoice'])
->name('orders.invoice')
->middleware('auth');