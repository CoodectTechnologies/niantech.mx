<?php

namespace App\Integrations\Odoo\Dto\Catalog;

use App\Integrations\Odoo\Client\OdooClient;

class WarehouseDto
{
    public function __construct(
        public readonly string $name,
        public readonly string $external,
        public readonly int $externalId,
        public readonly int $productId,
        public readonly float $quantity,
    ) {}

    public static function handle(array $warehouse): self {
        return new self(
            name: $warehouse['warehouse_id'][1] . ' - ' . $warehouse['location_id'][1],
            external: OdooClient::$code,
            externalId: $warehouse['warehouse_id'][0],
            productId: $warehouse['product_id'][0],
            quantity: $warehouse['quantity'] - $warehouse['reserved_quantity'],
        );
    }
    public function toArray(): array {
        return [
            'name' => $this->name,
            'external' => $this->external,
            'external_id' => $this->externalId,
            'quantity' => $this->quantity,
        ];
    }
    public static function groupByProduct(array $warehouseData): array {
        $result = [];
        foreach ($warehouseData as $warehouse) {
            $dto = self::handle($warehouse);
            $productId = $dto->productId;
            $warehouseId = $dto->externalId;

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
