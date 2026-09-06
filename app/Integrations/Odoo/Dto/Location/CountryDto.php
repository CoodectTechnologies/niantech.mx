<?php

namespace App\Integrations\Odoo\Dto\Location;

use App\Integrations\Odoo\Client\OdooClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class CountryDto
{
    public function __construct(
        public readonly string $external,
        public readonly int $externalId,
        public readonly ?string $code,
        public readonly string $name,
        public readonly ?string $phoneCode,
        public readonly ?array $statesIds,
        public readonly bool $status,
    ) {}

    public static function handle(array $country): self {
        try {
            return new self(
                external: OdooClient::$code,
                externalId: (int) ($country['id'] ?? 0),
                code: strtoupper(trim(strval($country['code'] ?? ''))),
                name: trim(strval($country['display_name'] ?? '')),
                phoneCode: trim(strval($country['phone_code'] ?? '')),
                statesIds: $country['state_ids'] ?? [],
                status: true,
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling country DTO: '.$e->getMessage(), [
                'country' => $country,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'external' => $this->external,
            'external_id' => $this->externalId,
            'code' => $this->code,
            'name' => $this->name,
            'phone_code' => $this->phoneCode,
            'states_ids' => $this->statesIds,
            'status' => $this->status,
        ];
    }
}
