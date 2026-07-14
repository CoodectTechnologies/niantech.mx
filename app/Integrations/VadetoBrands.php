<?php

namespace App\Integrations;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VadetoBrands
{
    public static $code = 'VADETO_BRANDS';

    /*  ========================================================================= */
    /*  PRODUCTS */
    /*  ========================================================================= */
    public function getProducts() {
        $url = 'productos/datos';
        $data = ['marca' => ''];
        try {
            if (! config('services.vadeto_brands.status')) {
                return [];
            }
            $response = $this->request('POST', $url, $data);
            if (isset($response['productos'])) {
                return $response;
            } else {
                $this->log('ERROR AL OBTENER LOS PRODUCTOS', $url, $data, $response);
            }
        } catch (Exception $e) {
            $this->log('Exception getProducts: '.$e->getMessage(), $url, $data);
        }

        return [];
    }
    public function getTemplates() {
        $url = 'plantillas';
        $data = ['marca' => ''];
        try {
            if (! config('services.vadeto_brands.status')) {
                return [];
            }
            $response = $this->request('POST', $url, $data);
            if (isset($response['plantillas'])) {
                return $response;
            } else {
                $this->log('ERROR AL OBTENER LAS PLANTILLAS', $url, $data, $response);
            }
        } catch (Exception $e) {
            $this->log('Exception getTemplates: '.$e->getMessage(), $url, $data);
        }

        return [];
    }

    /*  ========================================================================= */
    /*  CLOUD RESOURCES */
    /*  ========================================================================= */
    public function getCloudResources(string $brand, string $language, string|null $sku): array {
        $result = [];
        $url = 'cloud/script';
        $data = [
            'marca' => strtolower($brand),
            'idioma' => $language,
            'sku' => $sku,
        ];
        try {
            if (! config('services.vadeto_brands.status')) {
                return $result;
            }
            if (! in_array(strtolower($brand), config('services.vadeto_brands.allowed', []))) {
                return $result;
            }
            $response = $this->request('POST', $url, $data);
            if (isset($response['resources'])) {
                $result = $response;
            } else {
                $this->log('ERROR AL OBTENER LOS RECURSOS DE BRANDS', $url, $data, $response);
            }
        } catch (Exception $e) {
            $this->log('Exception getCloudResources: '.$e->getMessage(), $url, $data, $result);
        }

        return $result;
    }

    /*  ========================================================================= */
    /*  IMAGES */
    /*  ========================================================================= */
    public function getImages(string $brand, ?string $sku = null): array {
        $url = 'productos/imagenes';
        $data = [
            'marca' => strtolower($brand),
            'sku' => $sku,
        ];
        try {
            if (! config('services.vadeto_brands.status')) {
                return [];
            }
            if (! in_array(strtolower($brand), config('services.vadeto_brands.allowed'))) {
                return [];
            }
            $response = $this->request('POST', $url, $data);

            return $response;
        } catch (Exception $e) {
            $this->log('Exception getImages: '.$e->getMessage(), $url, $data);
        }

        return [];
    }

    /*  ========================================================================= */
    /*  REQUEST */
    /*  ========================================================================= */
    private function request(string $method, string $url, array $body = []) {
        $client = new Client(['base_uri' => config('services.vadeto_brands.url'), 'verify' => false]);
        $response = $client->request($method, $url, ['json' => array_merge([
            'user' => config('services.vadeto_brands.user'),
            'pass' => config('services.vadeto_brands.pass'),
        ], $body)]);
        $result = json_decode($response->getBody(), true);

        return $result;
    }

    /*  ========================================================================= */
    /*  LOG */
    /*  ========================================================================= */
    private function log(string $title, string $url, array $data = [], array $response = []) {
        Log::channel('vadeto_brands')->info($title);
        Log::channel('vadeto_brands')->info(config('services.vadeto_brands.url').$url);
        Log::channel('vadeto_brands')->info('data', $data);
        Log::channel('vadeto_brands')->info('response', $response);
        Log::channel('vadeto_brands')->info('================================================================================');
    }
}
