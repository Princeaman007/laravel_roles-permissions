<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

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

// Routes du panier
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

// Routes de checkout
Route::group(['middleware' => 'auth'], function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Compte client
    Route::get('/account', [CustomerController::class, 'index'])->name('account.index');
    Route::get('/account/orders', [CustomerController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [CustomerController::class, 'showOrder'])->name('account.orders.show');
    Route::get('/account/addresses', [CustomerController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [CustomerController::class, 'storeAddress'])->name('account.addresses.store');
});

// Routes admin (protégées)
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:Admin|Super Admin|Shop Manager']], function () {
    Route::resources([
        'categories' => CategoryController::class,
        'orders' => OrderController::class,
    ]);
    // Ordre des routes déjà défini dans votre code pour products, users et roles
});