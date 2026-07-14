<?php

namespace App\DTO\Integrations\Odoo\Location;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class CountryDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly int $providerId,
        public readonly ?string $code,
        public readonly string $name,
        public readonly ?string $phoneCode,
        public readonly ?array $statesIds,
        public readonly bool $status,
    ) {}

    public static function handle(array $country): self {
        try {
            return new self(
                provider: Odoo::$code,
                providerId: (int) ($country['id'] ?? 0),
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
            'provider' => $this->provider,
            'provider_id' => $this->providerId,
            'code' => $this->code,
            'name' => $this->name,
            'phone_code' => $this->phoneCode,
            'states_ids' => $this->statesIds,
            'status' => $this->status,
        ];
    }
}
