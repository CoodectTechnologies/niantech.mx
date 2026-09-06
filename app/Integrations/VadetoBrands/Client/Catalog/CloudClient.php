<?php

namespace App\Integrations\VadetoBrands\Client\Catalog;

use App\Integrations\VadetoBrands\Client\VadetoBrandsClient;
use Exception;

class CloudClient extends VadetoBrandsClient
{    
    public function getCloudResources(string $brand, string $language, string|null $sku): array {
        $result = [];
        $url = 'cloud/script';
        $data = [
            'marca' => strtolower($brand),
            'idioma' => $language,
            'sku' => $sku,
        ];
        try {
            if(!config('services.vadeto_brands.status')):
                return $result;
            endif;
            if(!in_array(strtolower($brand), config('services.vadeto_brands.allowed', []))):
                return $result;
            endif;
            $response = $this->request('POST', $url, $data);
            if(isset($response['resources'])):
                $result = $response;
            else:
                $this->log('warning', 'ERROR AL OBTENER LOS RECURSOS DE BRANDS', $url, $data, $response);
            endif;
        } catch (Exception $e) {
            $this->log('warning', 'Exception getCloudResources: '.$e->getMessage(), $url, $data, $result);
        }
        return $result;
    }
}
