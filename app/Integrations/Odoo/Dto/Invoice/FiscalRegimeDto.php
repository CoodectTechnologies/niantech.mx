<?php

namespace App\Integrations\Odoo\Dto\Invoice;

use App\Integrations\Odoo\Client\OdooClient;
use Illuminate\Support\Facades\Log;
use Throwable;

class FiscalRegimeDto
{
    public function __construct(
        public readonly string $external,
        public readonly string $code,
        public readonly string $description,
    ) {}

    public static function handle(array $fiscalRegime): self {
        try {
            return new self(
                external: OdooClient::$code,
                code: trim(strval($fiscalRegime[0] ?? '')),
                description: trim(strval($fiscalRegime[1] ?? '')),
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling fiscal regime DTO: '.$e->getMessage(), [
                'fiscal_regime' => $fiscalRegime,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'external' => $this->external,
            'code' => $this->code,
            'description' => $this->description,
        ];
    }
}
