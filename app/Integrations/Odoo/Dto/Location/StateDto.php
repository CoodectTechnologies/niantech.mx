<?php

namespace App\Integrations\Odoo\Dto\Location;

use App\Integrations\Odoo\Client\OdooClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class StateDto
{
    public function __construct(
        public readonly string $external,
        public readonly int $externalId,
        public readonly ?int $countryExternalId,
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $countryName,
    ) {}

    public static function handle(array $state): self {
        try {
            return new self(
                external: OdooClient::$code,
                externalId: (int) ($state['id'] ?? 0),
                countryExternalId: ! empty($state['country_id'][0]) ? (int) $state['country_id'][0] : null,
                name: trim(strval($state['name'] ?? '')),
                code: ! empty($state['code']) ? strtoupper(trim(strval($state['code']))) : null,
                countryName: ! empty($state['country_id'][1]) ? trim(strval($state['country_id'][1])) : null,
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling state DTO: '.$e->getMessage(), [
                'state' => $state,
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
            'country_external_id' => $this->countryExternalId,
            'name' => $this->name,
            'code' => $this->code,
            'country_name' => $this->countryName,
        ];
    }
}
