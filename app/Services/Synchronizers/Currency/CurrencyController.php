<?php

namespace App\Services\Synchronizers\Currency;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class CurrencyController extends Controller
{
    public function __construct() {}
    public static function save($currencyCode) {
        $currencyId = null;
        if ($currencyCode) {
            $currency = Currency::getCurrencyByCode($currencyCode);
            if ($currency) {
                $currencyId = $currency->id;
            }
        }
        if (! $currencyId) {
            $currency = Currency::getDefault();
            $currencyId = $currency->id;
        }

        return $currencyId;
    }
    public static function saveExchangeRate(): bool {
        $saved = false;
        activity()->withoutLogs(function () use (&$saved) {
            $currencyDefault = Currency::getDefault();
            if (! $currencyDefault) {
                return;
            }
            $currencies = Currency::where('active', true)->get();
            if ($currencies->isEmpty()) {
                return;
            }
            $response = Http::get('https://open.er-api.com/v6/latest/'.strtoupper($currencyDefault->code));
            if (! $response->successful()) {
                return;
            }
            $data = $response->json();
            if (data_get($data, 'result') !== 'success') {
                return;
            }
            $rates = data_get($data, 'rates', []);
            if (! is_array($rates) || empty($rates)) {
                return;
            }
            foreach ($currencies as $currency) {
                if (($currency->code === $currencyDefault->code) && $currency->value != 1) {
                    $currency->update(['value' => 1]);

                    continue;
                }
                $rate = $rates[strtoupper($currency->code)] ?? null;
                if (! is_numeric($rate) || floatval($rate) <= 0) {
                    continue;
                }
                $value = round(1 / floatval($rate), 4);
                $currency->update(['value' => $value]);
            }
            Cache::forget('currencies');
            $saved = true;
        });

        return $saved;
    }
}
