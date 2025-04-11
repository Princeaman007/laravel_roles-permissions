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

// ...
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

    // ✅ Route pour l'ajout d'adresse
    Route::post('/account/addresses', [AddressController::class, 'store'])->name('account.addresses.store');

    // Routes pour le compte utilisateur (version précédente)
    Route::get('/account', [CustomerController::class, 'index'])->name('account.index');
    Route::get('/account/orders', [CustomerController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [CustomerController::class, 'showOrder'])->name('account.orders.show');
    Route::get('/account/addresses', [CustomerController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [CustomerController::class, 'storeAddress'])->name('account.addresses.store');

    // Nouvelles routes pour le compte utilisateur avec AccountController
    Route::prefix('account')->name('account.')->group(function () {
        // Profil utilisateur
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');

        // Routes complémentaires pour les adresses
        Route::get('/addresses/create', [AccountController::class, 'createAddress'])->name('addresses.create');
        Route::get('/addresses/{address}/edit', [AccountController::class, 'editAddress'])->name('addresses.edit');
        Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [AccountController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::post('/addresses/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('addresses.default');

        // Liste de souhaits
        Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
        Route::post('/wishlist/add', [AccountController::class, 'addToWishlist'])->name('wishlist.add');
        Route::delete('/wishlist/{wishlistItem}', [AccountController::class, 'removeFromWishlist'])->name('wishlist.remove');
    });
}); // ✅ L’accolade fermante correcte est ici maintenant

// Routes admin (protégées)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:Admin|Super Admin|Shop Manager']], function () {
    Route::resources([
        'categories' => CategoryController::class,
        'orders' => OrderController::class,
    ]);
});
