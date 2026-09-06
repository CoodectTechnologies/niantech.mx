<?php

namespace App\Integrations\Odoo\Client\Catalog;

use App\Integrations\Odoo\Client\OdooClient;

class WarehouseClient extends OdooClient
{    
    public function getWarehouses(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/stock.quant/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['quantity', '>', 0], ['location_id.usage', '=', 'internal']], $domain),
            'fields' => ['product_id', 'location_id', 'quantity', 'reserved_quantity', 'warehouse_id'],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200], $params),
        ]];
        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getWarehouses: ', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
}
