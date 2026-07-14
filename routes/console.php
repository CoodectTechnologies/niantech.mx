<?php

use Illuminate\Support\Facades\Schedule;

// Test
// Schedule::command('test:test')->everyMinute()->withoutOverlapping();
// Sitemap
Schedule::command('sitemap:generate')->daily()->withoutOverlapping();
// Backup
Schedule::command('backup:run --only-db')->weekly()->withoutOverlapping();
// Promotion
Schedule::command('promotion:inactive')->daily()->withoutOverlapping();
// Cart
Schedule::command('cart:forgotten')->cron('0 0 */2 * *')->withoutOverlapping();
// User
Schedule::command('user:save')->daily()->withoutOverlapping();
// Address
Schedule::command('address:save')->daily()->withoutOverlapping();
// Invoice catalogs
Schedule::command('invoice:use-cfdi-save')->daily()->withoutOverlapping();
Schedule::command('invoice:fiscal-regime-save')->daily()->withoutOverlapping();
// Catalog
Schedule::command('catalog:product-save')->daily()->withoutOverlapping();
Schedule::command('catalog:product-status')->everyThirtyMinutes()->withoutOverlapping();
Schedule::command('catalog:product-warehouse')->everyTenMinutes()->withoutOverlapping();
Schedule::command('catalog:product-image')->daily()->withoutOverlapping();
Schedule::command('catalog:product-content')->daily()->withoutOverlapping();
// Order
Schedule::command('order:save')->everyTenMinutes()->withoutOverlapping();
// Invoice
Schedule::command('invoice:cancel')->hourly()->withoutOverlapping();
// Exchange rate
Schedule::command('currency:exchange-rate')->everyTenMinutes()->withoutOverlapping();