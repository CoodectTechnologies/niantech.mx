<?php

namespace App\Services\Integrations\Odoo\Order;

use App\DTO\Integrations\Odoo\Order\OrderDTO;
use App\Integrations\Odoo;
use Generator;

class OrderService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $orderId): array {
        $result = $this->odoo->getOrders(domain: [['id', '=', $orderId]]);
        $orderDto = OrderDTO::handle($result[0] ?? []);

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
        $rawOrders = $this->odoo->getOrders(domain: $domain, params: $params);
        foreach ($rawOrders as $orderData) {
            $orderDto = OrderDTO::handle($orderData);
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
}
