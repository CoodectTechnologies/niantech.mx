<?php

namespace App\Integrations\VadetoBrands\Client;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class VadetoBrandsClient
{
    public static $code = 'VADETO_BRANDS';
    
    public function request(string $method, string $url, array $body = []) {
        $client = new Client(['base_uri' => config('services.vadeto_brands.url'), 'verify' => false]);
        $response = $client->request($method, $url, ['json' => array_merge([
            'user' => config('services.vadeto_brands.user'),
            'pass' => config('services.vadeto_brands.pass'),
        ], $body)]);
        $result = json_decode($response->getBody(), true);

        return $result;
    }
    public function log(string $level, string $title, string $url, array $data = [], array $response = []) {
        Log::channel('vadeto_brands')->$level($title, [
            'url' => config('services.vadeto_brands.url').$url,
            'data' => $data,
            'response' => $response
        ]);
    }
}
