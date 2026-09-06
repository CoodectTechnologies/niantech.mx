<?php

namespace App\Integrations\Odoo\Client\Catalog;

use App\Integrations\Odoo\Client\OdooClient;

class ProductClient extends OdooClient
{    
    public function getProducts(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/product.template/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['sale_ok', '=', true], ['active', '=', true]], $domain),
            'fields' => ['id', 'name', 'default_code', 'list_price', 'standard_price', 'currency_id', 'qty_available', 'virtual_available', 'description_sale', 'description', 'categ_id', 'barcode', 'weight', 'volume'],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getProducts', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
}
