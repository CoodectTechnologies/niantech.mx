<?php

namespace App\Services\Synchronizers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\Odoo;
use App\Models\Country;
use App\Models\State;
use App\Services\Integrations\Odoo\Location\CountryService as OdooCountryService;
use App\Services\Integrations\Odoo\Location\StateService as OdooStateService;
use Illuminate\Support\Facades\Log;
use Throwable;

class LocationService extends Controller
{
    protected OdooCountryService $countryService;
    protected OdooStateService $stateService;

    public function __construct() {
        $this->countryService = new OdooCountryService;
        $this->stateService = new OdooStateService;
    }
    public function save(): array {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'created_countries' => 0,
                'updated_countries' => 0,
                'skipped_countries' => 0,
                'failed_countries' => 0,
                'created_states' => 0,
                'updated_states' => 0,
                'skipped_states' => 0,
                'failed_states' => 0,
            ];

            if (! config('services.odoo.status')) {
                $result['time'] = microtime(true) - $startTime;

                return $result;
            }

            $this->syncCountries($result);
            $this->syncStates($result);

            $result['time'] = microtime(true) - $startTime;

            return $result;
        });
    }
    protected function syncCountries(array &$result): void {
        $countriesAvaliables = ['MX'];
        $codeDefault = countryByLanguage(config('translatable.fallback'))['code'] ?? 'MX';
        $domain = [['code', 'in', $countriesAvaliables]];

        foreach ($this->countryService->getAll(domain: $domain) as $countries) {
            $chunkNow = now();

            $providerIds = array_values(array_filter(array_map(function ($countryData) { return $countryData['provider_id']; }, $countries)));
            $codes = array_values(array_filter(array_map(function ($countryData) { return $countryData['code']; }, $countries)));

            $existingByProvider = Country::query()->where('provider', Odoo::$code)->whereIn('provider_id', $providerIds)->get()->keyBy('provider_id');
            $existingByCode = Country::query()->whereIn('code', $codes)->get()->keyBy('code');

            $toUpdate = [];
            $toCreate = [];
            foreach ($countries as $countryData) {
                try {
                    $providerId = $countryData['provider_id'];
                    if (! $providerId) {
                        $result['skipped_countries'] += 1;

                        continue;
                    }

                    $code = $countryData['code'];
                    $existingCountry = $existingByProvider[$providerId] ?? null;
                    if (! $existingCountry && $code) {
                        $existingCountry = $existingByCode[$code] ?? null;
                    }

                    $updateRow = [
                        'provider' => $countryData['provider'],
                        'provider_id' => $providerId,
                        'name' => $countryData['name'],
                        'code' => $code,
                        'phonecode' => $countryData['phone_code'],
                        'status' => in_array($code, $countriesAvaliables, true),
                        'default' => $code == $codeDefault,
                        'updated_at' => $chunkNow,
                    ];

                    if ($existingCountry) {
                        $toUpdate[] = ['id' => $existingCountry->id, ...$updateRow];
                    } else {
                        $toCreate[] = [...$updateRow, 'created_at' => $chunkNow];
                    }
                } catch (Throwable $e) {
                    $result['failed_countries'] += 1;
                    Log::channel('odoo.general')->error('Error syncing country: '.$e->getMessage(), [
                        'country' => $countryData,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }

            if ($toUpdate) {
                Country::batchUpdate($toUpdate, 'id');
                $result['updated_countries'] += count($toUpdate);
            }
            if ($toCreate) {
                Country::insert($toCreate);
                $result['created_countries'] += count($toCreate);
            }
        }
        Country::query()->whereNotIn('code', $countriesAvaliables)->delete();
    }
    protected function syncStates(array &$result): void {
        $countriesByProvider = Country::query()->whereNotNull('provider_id')->get()->keyBy('provider_id');
        $countryProviderIds = $countriesByProvider->keys()->all();

        if (empty($countryProviderIds)) {
            return;
        }

        $domain = [['country_id', 'in', array_values($countryProviderIds)]];
        foreach ($this->stateService->getAll(domain: $domain) as $states) {
            $chunkNow = now();
            $providerIds = array_values(array_filter(array_map(function ($stateData) { return $stateData['provider_id']; }, $states)));
            $existingByProvider = State::query()->whereIn('provider_id', $providerIds)->get()->keyBy('provider_id');

            $toUpdate = [];
            $toCreate = [];
            foreach ($states as $stateData) {
                try {
                    $countryProviderId = $stateData['country_provider_id'];
                    $country = $countriesByProvider[$countryProviderId] ?? null;
                    if (! $country) {
                        $result['skipped_states'] += 1;

                        continue;
                    }

                    $providerId = $stateData['provider_id'];
                    if (! $providerId) {
                        $result['skipped_states'] += 1;

                        continue;
                    }

                    $state = $existingByProvider[$providerId] ?? null;

                    $row = [
                        'provider' => Odoo::$code,
                        'provider_id' => $providerId,
                        'country_id' => $country->id,
                        'name' => $stateData['name'],
                        'updated_at' => $chunkNow,
                    ];

                    if ($state) {
                        $toUpdate[] = ['id' => $state->id, ...$row];
                    } else {
                        $toCreate[] = [...$row, 'created_at' => $chunkNow];
                    }
                } catch (Throwable $e) {
                    $result['failed_states'] += 1;
                    Log::channel('odoo.general')->error('Error syncing state: '.$e->getMessage(), [
                        'state' => $stateData,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }

            if ($toUpdate) {
                State::batchUpdate($toUpdate, 'id');
                $result['updated_states'] += count($toUpdate);
            }

            if ($toCreate) {
                State::insert($toCreate);
                $result['created_states'] += count($toCreate);
            }
        }
        State::query()->whereNull('provider_id')->delete();
    }
}
