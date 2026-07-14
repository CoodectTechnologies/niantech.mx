<?php

namespace App\Services\Integrations\Odoo\Location;

use App\DTO\Integrations\Odoo\Location\CountryDTO;
use App\Integrations\Odoo;
use Generator;

class CountryService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function find(int $countryId): array {
        $result = $this->odoo->getCountries(domain: [['id', '=', $countryId]]);
        $countryDto = CountryDTO::handle($result[0] ?? []);

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

        $rawCountries = $this->odoo->getCountries(domain: $domain, params: $params);
        foreach ($rawCountries as $countryData) {
            $countryDto = CountryDTO::handle($countryData);
            $paginated[$countryDto->providerId] = $countryDto->toArray();
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
