<?php

namespace App\DTO\Integrations\Odoo\Location;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class StateDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly int $providerId,
        public readonly ?int $countryProviderId,
        public readonly string $name,
        public readonly ?string $code,
        public readonly ?string $countryName,
    ) {}

    public static function handle(array $state): self {
        try {
            return new self(
                provider: Odoo::$code,
                providerId: (int) ($state['id'] ?? 0),
                countryProviderId: ! empty($state['country_id'][0]) ? (int) $state['country_id'][0] : null,
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
            'provider' => $this->provider,
            'provider_id' => $this->providerId,
            'country_provider_id' => $this->countryProviderId,
            'name' => $this->name,
            'code' => $this->code,
            'country_name' => $this->countryName,
        ];
    }
}
