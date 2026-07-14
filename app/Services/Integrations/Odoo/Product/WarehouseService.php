<?php

namespace App\Services\Integrations\Odoo\Product;

use App\DTO\Integrations\Odoo\Product\WarehouseDTO;
use App\Integrations\Odoo;
use Generator;

class WarehouseService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
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
        $rawWarehouses = $this->odoo->getWarehouses(params: $params);
        $paginated = WarehouseDTO::groupByProduct($rawWarehouses);

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
