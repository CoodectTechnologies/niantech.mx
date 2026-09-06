<?php

namespace App\Services\Synchronizers\Address;

use App\Integrations\Odoo\Client\OdooClient;
use App\Models\Address;
use App\Models\FiscalRegime;
use App\Models\State;
use App\Models\UseCfdi;
use App\Models\User;
use App\Integrations\Odoo\Resources\Address\AddressResource;
use Illuminate\Support\Facades\Log;
use Throwable;

class AddressService
{
    protected AddressResource $addressResource;

    public function __construct() {
        $this->addressResource = new AddressResource;
    }
    public function save(): array {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'created' => 0,
                'updated' => 0,
                'deleted' => 0,
                'skipped' => 0,
                'failed' => 0,
            ];

            if (! config('services.odoo.status')) {
                $result['time'] = microtime(true) - $startTime;

                return $result;
            }

            User::query()
                ->whereNotNull('external_id')
                ->where('external_id', '!=', '')
                ->orderBy('id')
                ->chunkById(100, function ($users) use (&$result) {
                    foreach ($users as $user) {
                        $this->syncUserAddresses($user, $result);
                    }
                });

            $result['time'] = microtime(true) - $startTime;

            return $result;
        });
    }
    protected function syncUserAddresses(User $user, array &$result): void {
        try {
            $externalId = intval($user->external_id);
            if (! $externalId) {
                $result['skipped'] += 1;

                return;
            }

            $odooExternalIds = [];
            $domain = ['|', ['id', '=', $externalId], ['parent_id', '=', $externalId]];
            foreach ($this->addressResource->getAll(domain: $domain) as $addresses) {
                foreach ($addresses as $address) {
                    if (! empty($address['external_id'])) {
                        $odooExternalIds[] = (string) $address['external_id'];
                    }
                    $this->syncAddress($user, $address, $result);
                }
            }

            $this->deleteMissingAddresses(
                user: $user,
                externalIds: $odooExternalIds,
                result: $result
            );
        } catch (Throwable $e) {
            $result['failed'] += 1;
            Log::channel('odoo.general')->error('Error syncing user addresses: '.$e->getMessage(), [
                'user_id' => $user->id,
                'user_external_id' => $user->external_id,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }
    protected function syncAddress(User $user, array $address, array &$result): void {
        $addressType = strtolower(trim(strval($address['address_type'] ?? '')));
        $isBilling = ($address['external_id'] ?? null) == $user->external_id; // Si son mismo id de user que de address, quiere decir que es su dirección principal

        if (
            ! in_array($addressType, ['shipping', 'contact', 'billing'], true) ||
            ! $address['state_id'] || ! $address['street']
        ) {
            $result['skipped'] += 1;

            return;
        }

        $data = $this->buildAddressData($address, $isBilling);

        $addressLocal = Address::query()->firstOrNew([
            'user_id' => $user->id,
            'external' => OdooClient::$code,
            'external_id' => $address['external_id'] ?? null,
        ]);

        $isCreate = ! $addressLocal->exists;

        $addressLocal->fill($data);
        $addressLocal->saveQuietly();

        $result[$isCreate ? 'created' : 'updated'] += 1;
    }
    protected function buildAddressData(array $address, bool $isBilling): array {
        $state = State::query()->where('external_id', $address['state_id'] ?? null)->first();
        $fiscalRegime = ! empty($address['fiscal_regime']) ? FiscalRegime::where('code', $address['fiscal_regime'])->first() : null;
        $useCfdi = ! empty($address['use_cfdi']) ? UseCfdi::where('code', $address['use_cfdi'])->first() : null;

        return [
            'external' => OdooClient::$code,
            'external_id' => $address['external_id'] ?? null,
            'state_id' => $state->id ?? null,
            'fiscal_regime_id' => $fiscalRegime->id ?? null,
            'use_cfdi_id' => $useCfdi->id ?? null,
            'municipality' => trim(strval($address['municipality'] ?? '')),
            'colony' => trim(strval($address['colony'] ?? '')),
            'zip_code' => trim(strval($address['zip_code'] ?? '')),
            'street' => trim(strval($address['street'] ?? '')),
            'street_between' => $address['street_between'] ?? null,
            'street_references' => $address['street_references'] ?? null,
            'company' => $address['company'] ?? null,
            'vat' => $address['vat'] ?? null,
            'name' => $address['name'] ?? null,
            'phone' => $address['phone'] ?? null,
            'email' => $address['email'] ?? null,
            'is_default' => $isBilling,
            'is_billing' => $isBilling,
            'is_billing_default' => $isBilling,
        ];
    }
    protected function deleteMissingAddresses(User $user, array $externalIds, array &$result): void {
        $query = Address::query()->where('user_id', $user->id)->where('external', OdooClient::$code);
        if ($externalIds) {
            $query->whereNotIn('external_id', $externalIds);
        }
        $deleted = $query->count();
        if ($deleted) {
            $query->delete();
            $result['deleted'] = ($result['deleted'] ?? 0) + $deleted;
        }
    }
}
