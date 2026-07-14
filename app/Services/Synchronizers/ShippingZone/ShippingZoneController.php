<?php

namespace App\Services\Synchronizers\ShippingZone;

use App\Http\Controllers\Controller;
use App\Integrations\PCH;
use App\Models\Country;
use App\Models\ShippingZone;

class ShippingZoneController extends Controller
{
    public $provider;

    public function __construct() {
        $this->provider = new PCH;
    }
    public static function save() {
        $shippingZoneController = new self;
        $shippingZonesProvider = $shippingZoneController->provider->getShippingMethods();
        foreach ($shippingZonesProvider as $shippingCodeProvider => $shippingZoneProvider) {
            $shippingZone = ShippingZone::query()->where('provider_id', $shippingCodeProvider)->first();
            if (! $shippingZone) {
                self::create($shippingZoneProvider);
            }
        }
    }
    protected function create($shippingZoneProvider) {
        $country = Country::query()->with('states')->where('default', true)->first();
        $shippingZone = ShippingZone::create([
            'country_id' => $country->id,
            'name' => $shippingZoneProvider['name'],
            'alias' => $shippingZoneProvider['alias'],
            'provider' => $shippingZoneProvider['provider'],
            'provider_id' => $shippingZoneProvider['providerId'],
        ]);
        $stateIds = $country->states->pluck('id');
        $shippingZone->states()->sync($stateIds);
    }
}
