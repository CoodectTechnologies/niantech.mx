<?php

namespace App\Integrations\Odoo\Client\Location;

use App\Integrations\Odoo\Client\OdooClient;

class LocationClient extends OdooClient
{    
    public function getCountries(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.country/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([], $domain),
            'fields' => ['id', 'name', 'display_name', 'code', 'phone_code', 'state_ids'],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'name asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getCountries', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function getStates(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.country.state/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([], $domain),
            'fields' => ['id', 'name', 'code', 'country_id'],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'name asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getStates', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
}
