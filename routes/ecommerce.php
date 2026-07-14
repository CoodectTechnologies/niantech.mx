<?php

use App\Http\Controllers\Ecommerce\About\AboutController;
use App\Http\Controllers\Ecommerce\Account\Address\AddressController;
use App\Http\Controllers\Ecommerce\Account\Dashboard\DashboardController;
use App\Http\Controllers\Ecommerce\Account\Order\OrderController;
use App\Http\Controllers\Ecommerce\Account\ProductDigital\ProductDigitalController;
use App\Http\Controllers\Ecommerce\Account\Profile\ProfileController;
use App\Http\Controllers\Ecommerce\Blog\PostController;
use App\Http\Controllers\Ecommerce\Cart\CartController;
use App\Http\Controllers\Ecommerce\Category\CategoryController;
use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Http\Controllers\Ecommerce\Compare\CompareController;
use App\Http\Controllers\Ecommerce\Configurator\ConfiguratorController;
use App\Http\Controllers\Ecommerce\Contact\ContactController;
use App\Http\Controllers\Ecommerce\Currency\CurrencyController;
use App\Http\Controllers\Ecommerce\Feed\FacebookController;
use App\Http\Controllers\Ecommerce\Feed\GoogleController;
use App\Http\Controllers\Ecommerce\Gallery\GalleryController;
use App\Http\Controllers\Ecommerce\Home\HomeController;
use App\Http\Controllers\Ecommerce\Popup\PopupController;
use App\Http\Controllers\Ecommerce\PrivacyNotice\PrivacyNoticeController;
use App\Http\Controllers\Ecommerce\Product\ProductController;
use App\Http\Controllers\Ecommerce\Test\TestController;
use App\Http\Controllers\Ecommerce\Theme\ThemeController;
use App\Http\Controllers\Ecommerce\TrackOrder\TrackOrderController;
use App\Http\Controllers\Ecommerce\Wishlist\WishlistController;
use App\Http\Controllers\Web\Language\LanguageController;
use Illuminate\Support\Facades\Route;

Route::get('/test', [TestController::class, 'index']);
// Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Language
Route::get('/lang/{language}', LanguageController::class)->name('language');
// Theme
Route::get('/theme/{type}', ThemeController::class)->name('theme');
// Currency
Route::get('/currency/{currency}', CurrencyController::class)->name('currency');
// About
Route::get('/nosotros', [AboutController::class, 'index'])->name('about.index');
// Blog
Route::resource('/blog', PostController::class)->parameters(['blog' => 'post'])->names('blog');
// Gallery
Route::get('/galeria', [GalleryController::class, 'index'])->name('gallery.index');
// Contact
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
// Category
Route::resource('/categorias', CategoryController::class)->parameters(['categorias' => 'category'])->names('category');
// Product
Route::resource('/productos', ProductController::class)->parameters(['productos' => 'product'])->names('product');
// Cart
Route::middleware('cart')->get('/carrito', [CartController::class, 'index'])->name('cart.index');
// Wishlist
Route::middleware('cart')->get('/favoritos', [WishlistController::class, 'index'])->name('wishlist.index');
// Compare
Route::middleware('cart')->get('/comparar', [CompareController::class, 'index'])->name('compare.index');
// Track order
Route::get('/rastreo-de-pedido', [TrackOrderController::class, 'index'])->name('track-order.index');
// Popup
Route::get('/popup', [PopupController::class, 'index'])->name('popup.index');
// Configurator
Route::get('/arma-tu-pc', [ConfiguratorController::class, 'index'])->name('configurator.index');
// Account
Route::prefix('/cuenta')->middleware(['auth', 'verified'])->name('account.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('/direcciones', AddressController::class)->parameters(['direcciones' => 'address'])->names('address');
    Route::resource('/ordenes', OrderController::class)->parameters(['ordenes' => 'order'])->names('order');
    Route::resource('/mis-productos-digitales', ProductDigitalController::class)->parameters(['mis-productos-digitales' => 'product'])->names('product-digital');
    Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/password', [ProfileController::class, 'password'])->name('profile.password');
});
// Checkout
Route::prefix('/checkout')->name('checkout.')->group(function () {
    Route::middleware(['auth', 'verified', 'cart'])->get('/', [CheckoutController::class, 'index'])->name('index');
    Route::middleware(['auth', 'verified'])->get('/{order}/pago', [CheckoutController::class, 'payment'])->name('payment');
    Route::middleware(['auth', 'verified'])->get('/{order}/completo', [CheckoutController::class, 'complete'])->name('complete');
    Route::get('/whastapp', [CheckoutController::class, 'whatsapp'])->name('whatsapp');
});
// Feed
Route::prefix('feed')->name('feed.')->group(function () {
    Route::redirect('/', '/ecommerce/feed/facebook');
    Route::get('/facebook', [FacebookController::class, 'index'])->name('facebook.index');
    Route::get('/google', [GoogleController::class, 'index'])->name('google.index');
});
// Privacy notices
Route::get('/politicas/{privacyNotice}', [PrivacyNoticeController::class, 'show'])->name('privacy-notice.show');
