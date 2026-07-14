<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\ShippingZone;
use App\Models\State;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $country = Country::where('name', 'México')->first();

        if (! $country) {
            return;
        }

        $state = State::where('name', 'Jalisco')
            ->where('country_id', $country->id)
            ->first();

        $shippingZones = [
            [
                'country_id' => $country->id,
                'name' => 'ZMG - Zapopan.',
                'alias' => 'Estafeta',
                'zip_codes' => '45010...45245',
                'price' => 80,
                'shipping_days' => 4,
            ],
            [
                'country_id' => $country->id,
                'name' => 'ZMG - Guadalajara.',
                'alias' => 'Estafeta express',
                'zip_codes' => '44100...44987',
                'price' => 85,
                'shipping_days' => 3,
            ],
        ];

        foreach ($shippingZones as $shippingZone) {
            $zone = ShippingZone::updateOrCreate(
                [
                    'country_id' => $shippingZone['country_id'],
                    'name' => $shippingZone['name'],
                ],
                [
                    'alias' => $shippingZone['alias'],
                    'zip_codes' => $shippingZone['zip_codes'],
                    'price' => $shippingZone['price'],
                    'shipping_days' => $shippingZone['shipping_days'],
                ]
            );

            if ($state) {
                $zone->states()->syncWithoutDetaching([$state->id]);
            }
        }

        $todoMexico = ShippingZone::updateOrCreate(
            [
                'country_id' => $country->id,
                'name' => 'Todo méxico.',
            ],
            [
                'alias' => 'Estafeta standar',
                'zip_codes' => '',
                'price' => 99,
                'shipping_days' => 8,
            ]
        );

        $stateIds = State::where('country_id', $country->id)
            ->pluck('id')
            ->toArray();

        $todoMexico->states()->syncWithoutDetaching($stateIds);
    }
}
