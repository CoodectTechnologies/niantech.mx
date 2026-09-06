<?php

namespace App\Integrations\Odoo\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class OdooClient
{
    public static $code = 'ODOO';
    protected Client $client;
    protected mixed $response = [];

    public function __construct() {
        $this->client = new Client([
            'base_uri' => config('services.odoo.url'),
            'verify' => app()->isProduction(),
            'cookies' => true,
            'headers' => [
                'Authorization' => 'Bearer '.config('services.odoo.key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Odoo-Database' => config('services.odoo.database'),
            ],
        ]);
    }
    public function request(string $method, string $url, array $options = []): mixed {
        try {
            $this->response = $this->client->request($method, $url, $options);
            $result = json_decode($this->response->getBody()->getContents(), true);
            return $result;
        } catch (RequestException $e) {
            $this->response = $e->getResponse();
            $this->log('error', 'REQUESTEXCEPTION request: '.$e->getMessage(), $url, $options, $e);
            return json_decode($this->response?->getBody() ?? '', true);
        } catch (Throwable $e) {
            $this->log('error', 'THROWABLE request: '.$e->getMessage(), $url, $options, $e);
            return [];
        }
    }
    public function log(string $level, string $title, string $url, array $data = [], ?Throwable $exception = null) {
        Log::channel('odoo.api')->$level($title, [
            'url' => config('services.erp.url').$url,
            'data' => $data,
            'response_header' => $this->response?->getHeaders(),
            'response_data' => json_decode($this->response?->getBody() ?? '', true),
            'exception' => $exception 
                ? ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine()] 
                : []
        ]);
    }
}
