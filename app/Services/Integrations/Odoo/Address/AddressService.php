<?php

namespace App\Services\Integrations\Odoo\Address;

use App\DTO\Integrations\Odoo\Address\AddressDTO;
use App\Integrations\Odoo;
use App\Models\Address;
use Generator;

class AddressService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $addressId): array {
        $result = $this->odoo->getAddresses(domain: [['id', '=', $addressId]]);
        $addressDto = AddressDTO::handle($result[0] ?? []);

        return $addressDto->toArray() ?? [];
    }
    public function getAll(array $domain = [], array $params = [], bool $singleRequest = false): Generator {
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 1000;

        do {
            $result = $this->getPaginated($page, $perPage, $domain, $params);
            $hasNext = $result['paging']['has_next'];
            $page++;
            yield $result['data'];
        } while ($hasNext && ! $singleRequest);
    }
    public function getPaginated(int $page, int $perPage, array $domain = [], array $params = []): array {
        $paginated = [];
        $offset = ($page - 1) * $perPage;
        unset($params['page'], $params['per_page']);
        $params = array_merge($params, [
            'offset' => $offset,
            'limit' => $perPage,
        ]);

        $rawAddresses = $this->odoo->getAddresses(domain: $domain, params: $params);
        foreach ($rawAddresses as $addressData) {
            $addressDto = AddressDTO::handle($addressData);
            $paginated[$addressDto->providerId] = $addressDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawAddresses) === $perPage,
            ],
        ];
    }
    public function save(Address $address) {
        $stateProviderId = $address->state->provider_id ?? null;
        $countryProviderId = $address->state->country->provider_id ?? null;
        $userProviderId = $address->user->provider_id ?? null;

        if (! $stateProviderId || ! $countryProviderId || ! $userProviderId) {
            return [];
        }

        $data = [
            'name' => $address->is_billing ? $address->company : $address->name,
            'email' => $address->email ?? '',
            'phone' => $address->phone ?? '',
            'street' => $address->street ?? '',
            'street2' => $address->colony ?? '',
            'zip' => $address->zip_code ?? '',
            'city' => $address->municipality ?? '',
            'state_id' => $stateProviderId,
            'country_id' => $countryProviderId,
            'vat' => $address->vat ?? '',
            'l10n_mx_edi_fiscal_regime' => $address->fiscalRegime?->code ?? false,
            'l10n_mx_edi_usage' => $address->useCfdi?->code ?? false,
        ];

        if (! $address->is_billing) {
            $data['parent_id'] = (int) $userProviderId;
            $data['type'] = 'delivery';
        }

        $updateId = $address->is_billing ? $userProviderId : $address->provider_id;

        return ! $address->provider_id
            ? $this->create($data)
            : $this->update($updateId, $data);
    }
    public function create(array $data): array {
        $addressIds = $this->odoo->createAddress($data);
        if (($addressId = $addressIds[0] ?? null)) {
            $address = $this->find($addressId);

            return $address;
        }

        return [];
    }
    public function update(int $addressId, array $data): array {
        $updated = $this->odoo->updateAddress($addressId, $data);
        if ($updated) {
            $address = $this->find($addressId);

            return $address;
        }

        return [];
    }
    public function delete(int $addressId): bool {
        return $this->odoo->deleteAddress($addressId);
    }
}
