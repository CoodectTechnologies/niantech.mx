<?php

namespace App\Integrations\Odoo\Resources\Location;

use App\Integrations\Odoo\Client\Location\LocationClient;
use App\Integrations\Odoo\Dto\Location\StateDto;
use Generator;

class StateResource
{
    protected LocationClient $locationClient;

    public function __construct() {
        $this->locationClient = new LocationClient;
    }
    public function find(int $stateId): array {
        $result = $this->locationClient->getStates(domain: [['id', '=', $stateId]]);
        $stateDto = StateDto::handle($result[0] ?? []);

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

        $rawStates = $this->locationClient->getStates(domain: $domain, params: $params);
        foreach ($rawStates as $stateData) {
            $stateDto = StateDto::handle($stateData);
            $paginated[$stateDto->externalId] = $stateDto->toArray();
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
