<?php

namespace App\DTO\Integrations\VadetoBrands\Product;

use Illuminate\Support\Facades\Log;
use Throwable;

class TemplateDTO
{
    public function __construct(
        public readonly string $sku,
        public readonly array $description
    ) {}

    public static function handle(array $template, string $language, string $sku): self {
        try {
            $language = str_replace('-', '_', $language);
            $sku = strval(trim($sku));

            return new self(
                sku: $sku,
                description: [$language => $template['html'] ?? '']
            );
        } catch (Throwable $e) {
            Log::channel('vadeto_brands')->error('Error handling template DTO: '.$e->getMessage(), [
                'template' => $template,
                'sku' => $sku,
                'language' => $language,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'description' => $this->description,
        ];
    }
}
