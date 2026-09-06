<?php

namespace App\Integrations\Odoo\Resources\Order;

use App\Integrations\Odoo\Dto\Order\OrderDto;
use App\Integrations\Odoo\Client\Order\OrderClient;
use App\Models\Order;
use Generator;

class OrderResource
{
    protected OrderClient $orderClient;

    public function __construct() {
        $this->orderClient = new OrderClient;
    }
    public function find(int $orderId): array {
        $result = $this->orderClient->getOrders(domain: [['id', '=', $orderId]]);
        $orderDto = OrderDto::handle($result[0] ?? []);

        return $orderDto->toArray() ?? [];
    }
    public function getAll(array $domain = [], array $params = [], bool $singleRequest = false): Generator {
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 1000;
        do {
            $result = $this->getPaginated($page, $perPage, $domain, $params);
            $hasNext = $result['paging']['has_next'];
            $page++;
            yield $result['data'];
        } while ($hasNext && ! $singleRequest);
    }
    public function getPaginated(int $page, int $perPage, array $domain = [], array $params = []): array {
        $paginated = [];
        $offset = ($page - 1) * $perPage;
        unset($params['page'], $params['per_page']);
        $params = array_merge($params, [
            'offset' => $offset,
            'limit' => $perPage,
        ]);
        $rawOrders = $this->orderClient->getOrders(domain: $domain, params: $params);
        foreach($rawOrders as $orderData) {
            $orderDto = OrderDto::handle($orderData);
            $paginated[$orderDto->providerId] = $orderDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawOrders) === $perPage,
            ],
        ];
    }
    public function save(Order $order): array {
        $data = $this->buildOrderData($order);
        if(empty($data)) return [];

        if(!$order->provider_id):
            return $this->create($data);
        endif;
        return $this->find((int) $order->provider_id);
    }
    public function create(array $data): array {
        $result = [];
        $orderIds = $this->orderClient->createOrder($data);
        foreach($orderIds as $orderId):
            $result[] = $this->find((int) $orderId);
        endforeach;
        return $result;
    }
    public function buildOrderData(Order $order): array {
        $order->loadMissing([
            'user',
            'address',
            'billingAddress',
            'orderProducts.product',
            'orderProducts.orderProductWarehouses.productWarehouse',
        ]);

        $partnerId = $order->user?->provider_id;
        $billingAddressId = $order->billingAddress?->provider_id ?? $order->address?->provider_id;
        $shippingAddressId = $order->address?->provider_id ?? $billingAddressId;
        if(!$partnerId || !$billingAddressId || !$shippingAddressId) return [];

        $groupedByWarehouse = [];

        foreach($order->orderProducts as $orderProduct):
            $product = $orderProduct->product;
            $productProviderId = $product?->provider_id ?? null;
            if(!$productProviderId) continue;

            $warehouses = $orderProduct->orderProductWarehouses ?? collect();
            foreach($warehouses as $warehouseAssignment):
                $warehouseProviderId = $warehouseAssignment->productWarehouse?->provider_id ?? null;
                if(!$warehouseProviderId) continue;

                $groupedByWarehouse[$warehouseProviderId][] = [0, 0, [
                    'product_id' => (int) $productProviderId,
                    'product_uom_qty' => (float) ($warehouseAssignment->quantity),
                    'price_unit' => (float) $orderProduct->price,
                ]];
            endforeach;
        endforeach;
        if(empty($groupedByWarehouse)):
            return [];
        endif;

        $ordersPayload = [];
        foreach($groupedByWarehouse as $warehouseId => $lines):
            $ordersPayload[] = [
                'partner_id' => (int) $partnerId,
                'partner_invoice_id' => (int) $billingAddressId,
                'partner_shipping_id' => (int) $shippingAddressId,
                'warehouse_id' => (int) $warehouseId,
                'date_order' => now()->toDateTimeString(),
                'picking_policy' => 'direct',
                'order_line' => $lines,
            ];
        endforeach;
        return $ordersPayload;
    }
}
