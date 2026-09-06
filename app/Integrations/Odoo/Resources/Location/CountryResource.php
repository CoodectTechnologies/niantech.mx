<?php

namespace App\Integrations\Odoo\Resources\Location;

use App\Integrations\Odoo\Client\Location\LocationClient;
use App\Integrations\Odoo\Dto\Location\CountryDto;
use Generator;

class CountryResource
{
    protected LocationClient $locationClient;

    public function __construct() {
        $this->locationClient = new LocationClient;
    }
    public function find(int $countryId): array {
        $result = $this->locationClient->getCountries(domain: [['id', '=', $countryId]]);
        $countryDto = CountryDto::handle($result[0] ?? []);

        return $countryDto->toArray() ?? [];
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

        $rawCountries = $this->locationClient->getCountries(domain: $domain, params: $params);
        foreach ($rawCountries as $countryData) {
            $countryDto = CountryDto::handle($countryData);
            $paginated[$countryDto->externalId] = $countryDto->toArray();
        }

        return [
            'data' => $paginated,
            'paging' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'has_next' => count($rawCountries) === $perPage,
            ],
        ];
    }
}
