<?php

namespace App\Integrations\Odoo\Resources\Catalog;

use App\Integrations\Odoo\Client\Catalog\WarehouseClient;
use App\Integrations\Odoo\Dto\Catalog\WarehouseDto;
use Generator;

class WarehouseResource
{
    protected WarehouseClient $warehouseClient;

    public function __construct() {
        $this->warehouseClient = new WarehouseClient;
    }
    public function getAll(array $params = [], bool $singleRequest = false): Generator {
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 200;
        do {
            $result = $this->getPaginated($page, $perPage, $params);
            $hasNext = $result['paging']['has_next'];
            $page++;
            yield $result['data'];
        } while ($hasNext && ! $singleRequest);
    }
    public function getPaginated(int $page, int $perPage, array $params = []): array {
        $paginated = [];
        $offset = ($page - 1) * $perPage;
        unset($params['page'], $params['per_page']);
        $params = array_merge($params, [
            'offset' => $offset,
            'limit' => $perPage,
        ]);
        $rawWarehouses = $this->warehouseClient->getWarehouses(params: $params);
        $paginated = WarehouseDto::groupByProduct($rawWarehouses);

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawWarehouses) === $perPage,
            ],
        ];
    }
}
