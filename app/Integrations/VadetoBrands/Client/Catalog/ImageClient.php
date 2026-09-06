<?php

namespace App\Integrations\VadetoBrands\Client\Catalog;

use App\Integrations\VadetoBrands\Client\VadetoBrandsClient;
use Exception;

class ImageClient extends VadetoBrandsClient
{    
    public function getImages(string $brand, ?string $sku = null): array {
        $url = 'productos/imagenes';
        $data = [
            'marca' => strtolower($brand),
            'sku' => $sku,
        ];
        try {
            if(!config('services.vadeto_brands.status')):
                return [];
            endif;
            if(!in_array(strtolower($brand), config('services.vadeto_brands.allowed'))):
                return [];
            endif;
            $response = $this->request('POST', $url, $data);
            return $response;
        } catch (Exception $e) {
            $this->log('warning', 'Exception getImages: '.$e->getMessage(), $url, $data);
        }
        return [];
    }
}
