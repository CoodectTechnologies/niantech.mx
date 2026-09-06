<?php

namespace App\Integrations\Odoo\Client\Address;

use App\Integrations\Odoo\Client\OdooClient;

class AddressClient extends OdooClient
{    
    public function getAddresses(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.partner/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['active', '=', true], ['type', 'in', ['invoice', 'delivery', 'contact']]], $domain),
            'fields' => [
                'id', 'name', 'display_name', 'email', 'phone', 'vat', 'l10n_mx_edi_fiscal_regime', 'l10n_mx_edi_usage',
                'street', 'street2', 'zip', 'city', 'state_id', 'country_id',
                'lang', 'tz', 'is_company', 'company_type', 'customer_rank', 'supplier_rank',
                'create_date', 'write_date', 'active', 'type', 'parent_id',
            ],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'id asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getAddresses', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function createAddress(array $data): array|int {
        $method = 'POST';
        $url = 'json/2/res.partner/create';
        $options = ['json' => [
            'vals_list' => [$data],
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('error', 'BADREQUEST createAddress', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function updateAddress(int $addressId, array $data): bool {
        $method = 'POST';
        $url = 'json/2/res.partner/write';
        $options = ['json' => [
            'ids' => [$addressId],
            'vals' => $data,
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST updateAddress', $url, $options, $response);
            return false;
        endif;
        return $response;
    }
    public function deleteAddress(int $addressId): bool {
        $method = 'POST';
        $url = 'json/2/res.partner/unlink';
        $options = ['json' => [
            'ids' => [$addressId],
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST deleteAddress', $url, $options, $response);
            return false;
        endif;
        return (bool) $response;
    }
}
