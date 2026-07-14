<?php

namespace App\Services\Integrations\VadetoBrands\Product;

use App\DTO\Integrations\VadetoBrands\Product\CloudResourceDTO;
use App\Integrations\VadetoBrands;

class CloudResourceService
{
    protected VadetoBrands $vadetoBrands;

    public function __construct() {
        $this->vadetoBrands = new VadetoBrands;
    }
    public function find(string $brand, string $language, string|null $sku): array {
        $rawCloudResources = $this->vadetoBrands->getCloudResources($brand, $language, $sku);
        $cloudResourceDto = CloudResourceDTO::handle($rawCloudResources, $brand, $language, $sku);
        return $cloudResourceDto->toArray();
    }
}
