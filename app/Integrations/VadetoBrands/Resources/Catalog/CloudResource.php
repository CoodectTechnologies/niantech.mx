<?php

namespace App\Integrations\VadetoBrands\Resources\Catalog;

use App\Integrations\VadetoBrands\Client\Catalog\CloudClient;
use App\Integrations\VadetoBrands\Dto\Catalog\CloudDto;

class CloudResource
{
    protected CloudClient $cloudClient;

    public function __construct() {
        $this->cloudClient = new CloudClient;
    }
    public function find(string $brand, string $language, string|null $sku): array {
        $rawCloudResources = $this->cloudClient->getCloudResources($brand, $language, $sku);
        $cloudDto = CloudDto::handle($rawCloudResources, $brand, $language, $sku);
        return $cloudDto->toArray();
    }
}
