<?php

namespace App\DTO\Integrations\Odoo\Product;

use App\Integrations\Odoo;

class ProductCategoryDTO
{
    public function __construct(
        public readonly string $provider,
        public readonly array $name,
        public readonly array $description,
        public readonly ?array $children = null
    ) {}

    public static function handle(array $odooCategory): array {
        if (empty($odooCategory) || ! is_array($odooCategory)) {
            return [];
        }

        $categoryPath = $odooCategory[1] ?? '';
        $parts = array_map('trim', explode(' / ', $categoryPath));

        return self::buildCategoryTree($parts);
    }
    private static function buildCategoryTree(array $parts): array {
        $tree = [];
        $currentNode = &$tree;
        foreach ($parts as $index => $partName) {
            $formattedNode = [
                'name' => [config('translatable.fallback') => $partName],
                'description' => [config('translatable.fallback') => ''],
                'provider' => Odoo::$code,
            ];
            if ($index < count($parts) - 1) {
                $formattedNode['children'] = [];
            }
            $currentNode[] = $formattedNode;
            if (isset($currentNode[0]['children'])) {
                $currentNode = &$currentNode[0]['children'];
            }
        }

        return $tree;
    }
    public function toArray(): array {
        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'provider' => $this->provider,
        ];
        if ($this->children !== null) {
            $data['children'] = $this->children;
        }

        return $data;
    }
}
