<?php

namespace App\DTO\Integrations\Odoo\Address;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class AddressDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly int $providerId,
        public readonly ?int $customerProviderId,
        public readonly ?string $customerName,
        public readonly string $addressType,
        public readonly string $name,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $vat,
        public readonly ?string $fiscalRegime,
        public readonly ?string $useCfdi,
        public readonly ?string $company,
        public readonly ?int $countryId,
        public readonly ?string $countryName,
        public readonly ?int $stateId,
        public readonly ?string $stateName,
        public readonly ?string $municipality,
        public readonly ?string $colony,
        public readonly ?string $zipCode,
        public readonly ?string $street,
        public readonly ?string $streetBetween,
        public readonly ?string $streetReferences,
        public readonly bool $isDefault,
        public readonly bool $active,
        public readonly ?string $createdAt,
        public readonly ?string $updatedAt,
    ) {}

    public static function handle(array $address): self {
        try {
            $rawType = (string) ($address['type'] ?? '');
            $addressType = match ($rawType) {
                'invoice' => 'billing',
                'delivery' => 'shipping',
                default => $rawType,
            };

            return new self(
                provider: Odoo::$code,
                providerId: (int) ($address['id'] ?? 0),
                customerProviderId: ! empty($address['parent_id'][0]) ? (int) $address['parent_id'][0] : null,
                customerName: ! empty($address['parent_id'][1]) ? (string) $address['parent_id'][1] : null,
                addressType: $addressType,
                name: (string) ($address['display_name'] ?? $address['name'] ?? ''),
                email: ! empty($address['email']) ? (string) $address['email'] : null,
                phone: ! empty($address['phone']) ? (string) $address['phone'] : null,
                vat: ! empty($address['vat']) ? (string) $address['vat'] : null,
                fiscalRegime: $address['l10n_mx_edi_fiscal_regime'] ?? null,
                useCfdi: $address['l10n_mx_edi_usage'] ?? null,
                company: ! empty($address['is_company'])
                    ? (string) ($address['name'] ?? '')
                    : (! empty($address['parent_id'][1]) ? (string) $address['parent_id'][1] : null),
                countryId: ! empty($address['country_id'][0]) ? (int) $address['country_id'][0] : null,
                countryName: ! empty($address['country_id'][1]) ? (string) $address['country_id'][1] : null,
                stateId: ! empty($address['state_id'][0]) ? (int) $address['state_id'][0] : null,
                stateName: ! empty($address['state_id'][1]) ? (string) $address['state_id'][1] : null,
                municipality: ! empty($address['city']) ? (string) $address['city'] : null,
                colony: ! empty($address['street2']) ? (string) $address['street2'] : null,
                zipCode: ! empty($address['zip']) ? (string) $address['zip'] : null,
                street: ! empty($address['street']) ? (string) $address['street'] : null,
                streetBetween: null,
                streetReferences: null,
                isDefault: false,
                active: (bool) ($address['active'] ?? false),
                createdAt: ! empty($address['create_date']) ? (string) $address['create_date'] : null,
                updatedAt: ! empty($address['write_date']) ? (string) $address['write_date'] : null,
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling address DTO: '.$e->getMessage(), [
                'address' => $address,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'provider' => $this->provider,
            'provider_id' => $this->providerId,
            'customer_provider_id' => $this->customerProviderId,
            'customer_name' => $this->customerName,
            'address_type' => $this->addressType,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'vat' => $this->vat,
            'fiscal_regime' => $this->fiscalRegime,
            'use_cfdi' => $this->useCfdi,
            'company' => $this->company,
            'country_id' => $this->countryId,
            'country_name' => $this->countryName,
            'state_id' => $this->stateId,
            'state_name' => $this->stateName,
            'municipality' => $this->municipality,
            'colony' => $this->colony,
            'zip_code' => $this->zipCode,
            'street' => $this->street,
            'street_between' => $this->streetBetween,
            'street_references' => $this->streetReferences,
            'is_default' => $this->isDefault,
            'active' => $this->active,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
