<?php

namespace App\DTO\Integrations\Odoo\Product;

use App\Integrations\Odoo;

class WarehouseDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $provider,
        public readonly int $providerId,
        public readonly int $productId,
        public readonly float $quantity
    ) {}

    public static function handle(array $warehouse): self {
        return new self(
            name: $warehouse['location_id'][1],
            provider: Odoo::$code,
            providerId: $warehouse['location_id'][0],
            productId: $warehouse['product_id'][0],
            quantity: $warehouse['quantity'] - $warehouse['reserved_quantity']
        );
    }
    public function toArray(): array {
        return [
            'name' => $this->name,
            'provider' => $this->provider,
            'provider_id' => $this->providerId,
            'quantity' => $this->quantity,
        ];
    }
    public static function groupByProduct(array $warehouseData): array {
        $result = [];
        foreach ($warehouseData as $warehouse) {
            $dto = self::handle($warehouse);
            $productId = $dto->productId;
            $warehouseId = $dto->providerId;

            if (! isset($result[$productId])) {
                $result[$productId] = [];
            }
            if (! isset($result[$productId][$warehouseId])) {
                $result[$productId][$warehouseId] = $dto->toArray();
            } else {
                $result[$productId][$warehouseId]['quantity'] += $dto->quantity;
            }
        }

        return $result;
    }
}
