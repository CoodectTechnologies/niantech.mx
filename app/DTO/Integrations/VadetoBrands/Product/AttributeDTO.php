<?php

namespace App\DTO\Integrations\VadetoBrands\Product;

class AttributeDTO
{
    public function __construct(
        public readonly array $key,
        public readonly array $value
    ) {}

    public static function handle(array $attributes, string $language): array {
        $result = [];
        foreach ($attributes as $order => $attribute) {
            $key = $attribute['indice'] ?? '';
            $value = $attribute['value'] ?? '';
            if (! isset($result[$order])) {
                $result[$order] = [
                    'key' => [],
                    'value' => [],
                ];
            }
            $result[$order]['key'][$language] = $key;
            $result[$order]['value'][$language] = $value;
        }

        return $result;
    }
    public function toArray(): array {
        return [
            'key' => $this->key,
            'value' => $this->value,
        ];
    }
}
