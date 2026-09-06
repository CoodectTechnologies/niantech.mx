<?php

namespace App\Integrations\VadetoBrands\Resources\Catalog;

use App\Integrations\VadetoBrands\Client\Catalog\ImageClient;
use App\Integrations\VadetoBrands\Dto\Catalog\ImageDto;

class ImageResource
{
    protected ImageClient $imageClient;

    public function __construct() {
        $this->imageClient = new ImageClient;
    }
    public function getAll(string $brand, ?string $language = null, ?string $sku = null): array {
        $result = [];
        $rawImages = $this->imageClient->getImages($brand, $sku);
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
                $imageDto = ImageDto::handle($images, $rawLanguage, $currentSku);
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
