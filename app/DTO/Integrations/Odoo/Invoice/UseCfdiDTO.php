<?php

namespace App\DTO\Integrations\Odoo\Invoice;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class UseCfdiDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly string $code,
        public readonly string $description
    ) {}

    public static function handle(array $useCfdi): self {
        try {
            return new self(
                provider: Odoo::$code,
                code: strtoupper(trim(strval($useCfdi[0] ?? ''))),
                description: trim(strval($useCfdi[1] ?? '')),
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling use cfdi DTO: '.$e->getMessage(), [
                'use_cfdi' => $useCfdi,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'provider' => $this->provider,
            'code' => $this->code,
            'description' => $this->description,
        ];
    }
}
