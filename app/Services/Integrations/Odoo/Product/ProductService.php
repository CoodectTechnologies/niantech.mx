<?php

namespace App\Services\Integrations\Odoo\Product;

use App\DTO\Integrations\Odoo\Product\ProductDTO;
use App\Integrations\Odoo;
use Generator;

class ProductService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $productId): array {
        $result = $this->odoo->getProducts(domain: [['id', '=', $productId]]);
        $productDto = ProductDTO::handle($result[0] ?? []);

        return $productDto->toArray() ?? [];
    }
    public function getAll(array $params = [], bool $singleRequest = false): Generator {
        $page = $params['page'] ?? 1;
        $perPage = $params['per_page'] ?? 1000;
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
        $rawProducts = $this->odoo->getProducts(params: $params);
        foreach ($rawProducts as $productData) {
            $productDto = ProductDTO::handle($productData);
            $paginated[$productDto->providerId] = $productDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawProducts) === $perPage,
            ],
        ];
    }
}
