<?php

namespace App\Integrations\Odoo\Resources\Catalog;

use App\Integrations\Odoo\Client\Catalog\ProductClient;
use App\Integrations\Odoo\Dto\Catalog\ProductDto;
use Generator;

class ProductResource
{
    protected ProductClient $productClient;

    public function __construct() {
        $this->productClient = new ProductClient;
    }
    public function find(int $productId): array {
        $result = $this->productClient->getProducts(domain: [['id', '=', $productId]]);
        $productDto = ProductDto::handle($result[0] ?? []);

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
        $rawProducts = $this->productClient->getProducts(params: $params);
        foreach ($rawProducts as $productData) {
            $productDto = ProductDto::handle($productData);
            $paginated[$productDto->externalId] = $productDto->toArray();
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
