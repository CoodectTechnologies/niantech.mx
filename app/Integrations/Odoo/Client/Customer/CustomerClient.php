<?php

namespace App\Integrations\Odoo\Client\Customer;

use App\Integrations\Odoo\Client\OdooClient;

class CustomerClient extends OdooClient
{    
    public function getCustomers(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.partner/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['customer_rank', '>', 0], ['active', '=', true]], $domain),
            'fields' => [
                'id', 'name', 'display_name', 'email', 'phone', 'vat',
                'street', 'street2', 'zip', 'city', 'state_id', 'country_id',
                'lang', 'tz', 'is_company', 'company_type', 'customer_rank', 'supplier_rank',
                'create_date', 'write_date', 'active',
            ],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'id asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getCustomers', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function createCustomer(array $data): array|int {
        $method = 'POST';
        $url = 'json/2/res.partner/create';
        $options = ['json' => [
            'vals_list' => [array_merge($data, ['customer_rank' => 1])],
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('error', 'BADREQUEST createCustomer', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function updateCustomer(int $customerId, array $data): bool {
        $method = 'POST';
        $url = 'json/2/res.partner/write';
        $options = ['json' => [
            'ids' => [$customerId],
            'vals' => $data,
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('error', 'BADREQUEST updateCustomer', $url, $options, $response);
            return false;
        endif;
        return true;
    }
}
