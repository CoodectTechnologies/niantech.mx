<?php

namespace App\Services\Integrations\VadetoBrands\Product;

use App\DTO\Integrations\VadetoBrands\Product\ImageDTO;
use App\Integrations\VadetoBrands;

class ImageService
{
    protected VadetoBrands $vadetoBrands;

    public function __construct() {
        $this->vadetoBrands = new VadetoBrands;
    }
    public function getAll(string $brand, ?string $language = null, ?string $sku = null): array {
        $result = [];
        $rawImages = $this->vadetoBrands->getImages($brand, $sku);
        foreach ($rawImages['data']['images'] ?? [] as $rawLanguage => $products) {
            $rawLanguage = str_replace('-', '_', $rawLanguage);
            if ($language && ! ($language === $rawLanguage)) {
                continue;
            }
            foreach ($products as $currentSku => $images) {
                $currentSku = strval(trim($currentSku));
                if ($sku && $currentSku !== strval(trim($sku))) {
                    continue;
                }
                $imageDto = ImageDTO::handle($images, $rawLanguage, $currentSku);
                $imageData = $imageDto->toArray();
                if (! count($imageData['urls'])) {
                    continue;
                }
                $result[$imageData['language']][$imageData['sku']] = $imageData['urls'];
            }
        }

        return $result;
    }
}
