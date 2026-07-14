<?php

namespace App\Services\Integrations\Odoo\Location;

use App\DTO\Integrations\Odoo\Location\StateDTO;
use App\Integrations\Odoo;
use Generator;

class StateService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $stateId): array {
        $result = $this->odoo->getStates(domain: [['id', '=', $stateId]]);
        $stateDto = StateDTO::handle($result[0] ?? []);

        return $stateDto->toArray() ?? [];
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

        $rawStates = $this->odoo->getStates(domain: $domain, params: $params);
        foreach ($rawStates as $stateData) {
            $stateDto = StateDTO::handle($stateData);
            $paginated[$stateDto->providerId] = $stateDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawStates) === $perPage,
            ],
        ];
    }
}
