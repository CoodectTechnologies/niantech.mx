<?php

use App\Http\Controllers\Admin\Test\TestController;
use App\Http\Controllers\Web\About\AboutController;
use App\Http\Controllers\Web\Blog\BlogController;
use App\Http\Controllers\Web\Contact\ContactController;
use App\Http\Controllers\Web\Home\HomeController;
use App\Http\Controllers\Web\Language\LanguageController;
use App\Http\Controllers\Web\PrivacyNotice\PrivacyNoticeController;
use App\Http\Controllers\Web\Service\ServiceController;
use App\Http\Controllers\Web\Webhook\WebhookMercadopagoController;
use App\Http\Controllers\Web\Webhook\WebhookOpenpayBbvaController;
use App\Http\Controllers\Web\Webhook\WebhookStripeCashierController;
use App\Http\Controllers\Web\Webhook\WebhookStripeController;
use Illuminate\Support\Facades\Route;

// Test
Route::get('/test', [TestController::class, 'index'])->name('test.index');
// Home
Route::get('/', [HomeController::class, 'index'])->name('home.index');
// Language
Route::get('/lang/{language}', LanguageController::class)->name('language');
// About
Route::get('/nosotros', [AboutController::class, 'index'])->name('about.index');
// Service
Route::resource('/servicios', ServiceController::class)->parameters(['servicios' => 'service'])->only(['index', 'show'])->names('service');
// Blog
Route::resource('/blog', BlogController::class)->parameters(['blog' => 'post'])->only(['index', 'show'])->names('blog');
// Contact
Route::get('/contacto', [ContactController::class, 'index'])->name('contact.index');
// Privacy notice
Route::get('/politicas/{privacyNotice}', [PrivacyNoticeController::class, 'show'])->name('privacy-notice.show');
// Webhooks
Route::prefix('/webhook')->name('webhook.')->group(function () {
    Route::post('/stripe', WebhookStripeController::class)->name('payment.stripe')->withoutMiddleware('web');
    Route::post('/stripe/cashier', [WebhookStripeCashierController::class, 'handleWebhook'])->name('cashier.webhook')->withoutMiddleware('web');
    Route::post('/mercadopago', WebhookMercadopagoController::class)->name('payment.mercadopago')->withoutMiddleware('web');
    Route::post('/openpaybbva', WebhookOpenpayBbvaController::class)->name('payment.openpaybbva')->withoutMiddleware('web');
});
