<?php

namespace App\DTO\Integrations\Odoo\Customer;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class CustomerDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly int $providerId,
        public readonly string $name,
        public readonly string $displayName,
        public readonly ?string $email,
        public readonly ?string $phone,
        public readonly ?string $vat,
        public readonly ?int $countryId,
        public readonly ?string $countryName,
        public readonly ?string $lang,
        public readonly ?string $tz,
        public readonly bool $isCompany,
        public readonly ?string $companyType,
        public readonly int $customerRank,
        public readonly int $supplierRank,
        public readonly ?string $createDate,
        public readonly ?string $writeDate,
        public readonly bool $active,
    ) {}

    public static function handle(array $customer): self {
        try {
            return new self(
                provider: Odoo::$code,
                providerId: (int) ($customer['id'] ?? 0),
                name: (string) ($customer['name'] ?? ''),
                displayName: (string) ($customer['display_name'] ?? ''),
                email: (string) strtolower(trim(($customer['email'] ?? ''))),
                phone: (string) ($customer['phone'] ?? ''),
                vat: (string) ($customer['vat'] ?? ''),
                countryId: (int) ($customer['country_id'][0] ?? 0),
                countryName: (string) (($customer['country_id'][1] ?? '')),
                lang: (string) ($customer['lang'] ?? ''),
                tz: (string) ($customer['tz'] ?? ''),
                isCompany: (bool) ($customer['is_company'] ?? false),
                companyType: (string) ($customer['company_type'] ?? ''),
                customerRank: (int) ($customer['customer_rank'] ?? 0),
                supplierRank: (int) ($customer['supplier_rank'] ?? 0),
                createDate: (string) ($customer['create_date'] ?? ''),
                writeDate: (string) ($customer['write_date'] ?? ''),
                active: (bool) ($customer['active'] ?? false),
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling customer DTO: '.$e->getMessage(), [
                'customer' => $customer,
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
            'name' => $this->name,
            'display_name' => $this->displayName,
            'email' => $this->email,
            'phone' => $this->phone,
            'vat' => $this->vat,
            'country_id' => $this->countryId,
            'country_name' => $this->countryName,
            'lang' => $this->lang,
            'tz' => $this->tz,
            'is_company' => $this->isCompany,
            'company_type' => $this->companyType,
            'customer_rank' => $this->customerRank,
            'supplier_rank' => $this->supplierRank,
            'create_date' => $this->createDate,
            'write_date' => $this->writeDate,
            'active' => $this->active,
        ];
    }
}
