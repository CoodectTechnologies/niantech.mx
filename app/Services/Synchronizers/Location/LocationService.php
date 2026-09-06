<?php

namespace App\Services\Synchronizers\Location;

use App\Http\Controllers\Controller;
use App\Integrations\Odoo\Client\OdooClient;
use App\Integrations\Odoo\Resources\Location\CountryResource;
use App\Integrations\Odoo\Resources\Location\StateResource;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Log;
use Throwable;

class LocationService extends Controller
{
    protected CountryResource $countryResource;
    protected StateResource $stateResource;

    public function __construct() {
        $this->countryResource = new CountryResource;
        $this->stateResource = new StateResource;
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

        foreach ($this->countryResource->getAll(domain: $domain) as $countries) {
            $chunkNow = now();

            $externalIds = array_values(array_filter(array_map(function ($countryData) { return $countryData['external_id']; }, $countries)));
            $codes = array_values(array_filter(array_map(function ($countryData) { return $countryData['code']; }, $countries)));

            $existingByProvider = Country::query()->where('external', OdooClient::$code)->whereIn('external_id', $externalIds)->get()->keyBy('external_id');
            $existingByCode = Country::query()->whereIn('code', $codes)->get()->keyBy('code');

            $toUpdate = [];
            $toCreate = [];
            foreach ($countries as $countryData) {
                try {
                    $externalId = $countryData['external_id'];
                    if (! $externalId) {
                        $result['skipped_countries'] += 1;

                        continue;
                    }

                    $code = $countryData['code'];
                    $existingCountry = $existingByProvider[$externalId] ?? null;
                    if (! $existingCountry && $code) {
                        $existingCountry = $existingByCode[$code] ?? null;
                    }

                    $updateRow = [
                        'external' => $countryData['external'],
                        'external_id' => $externalId,
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
        $countriesByProvider = Country::query()->whereNotNull('external_id')->get()->keyBy('external_id');
        $countryExternalIds = $countriesByProvider->keys()->all();

        if (empty($countryExternalIds)) {
            return;
        }

        $domain = [['country_id', 'in', array_values($countryExternalIds)]];
        foreach ($this->stateResource->getAll(domain: $domain) as $states) {
            $chunkNow = now();
            $externalIds = array_values(array_filter(array_map(function ($stateData) { return $stateData['external_id']; }, $states)));
            $existingByProvider = State::query()->whereIn('external_id', $externalIds)->get()->keyBy('external_id');

            $toUpdate = [];
            $toCreate = [];
            foreach ($states as $stateData) {
                try {
                    $countryExternalId = $stateData['country_external_id'];
                    $country = $countriesByProvider[$countryExternalId] ?? null;
                    if (! $country) {
                        $result['skipped_states'] += 1;

                        continue;
                    }

                    $externalId = $stateData['external_id'];
                    if (! $externalId) {
                        $result['skipped_states'] += 1;

                        continue;
                    }

                    $state = $existingByProvider[$externalId] ?? null;

                    $row = [
                        'external' => OdooClient::$code,
                        'external_id' => $externalId,
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
        State::query()->whereNull('external_id')->delete();
    }
}
