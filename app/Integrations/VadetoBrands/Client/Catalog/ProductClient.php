<?php

namespace App\Integrations\VadetoBrands\Client\Catalog;

use App\Integrations\VadetoBrands\Client\VadetoBrandsClient;
use Exception;

class ProductClient extends VadetoBrandsClient
{    
    public function getProducts() {
        $url = 'productos/datos';
        $data = ['marca' => ''];
        try {
            if(!config('services.vadeto_brands.status')):
                return [];
            endif;
            $response = $this->request('POST', $url, $data);
            if(isset($response['productos'])):
                return $response;
            else:
                $this->log('warning', 'ERROR AL OBTENER LOS PRODUCTOS', $url, $data, $response);
            endif;
        } catch (Exception $e) {
            $this->log('warning', 'Exception getProducts: '.$e->getMessage(), $url, $data);
        }
        return [];
    }
    public function getTemplates() {
        $url = 'plantillas';
        $data = ['marca' => ''];
        try {
            if(!config('services.vadeto_brands.status')):
                return [];
            endif;
            $response = $this->request('POST', $url, $data);
            if(isset($response['plantillas'])):
                return $response;
            else:
                $this->log('warning', 'ERROR AL OBTENER LAS PLANTILLAS', $url, $data, $response);
            endif;
        } catch (Exception $e) {
            $this->log('warning', 'Exception getTemplates: '.$e->getMessage(), $url, $data);
        }
        return [];
    }
}
