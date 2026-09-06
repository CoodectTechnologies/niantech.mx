<?php

namespace App\Integrations\Odoo\Client\Order;

use App\Integrations\Odoo\Client\OdooClient;

class OrderClient extends OdooClient
{    
    public function getOrders(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/sale.order/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['state', 'in', ['sale', 'done']]], $domain),
            'fields' => [
                'id', 'name', 'display_name', 'partner_id', 'partner_invoice_id', 'partner_shipping_id', 'date_order', 'create_date', 'write_date', 'commitment_date', 'validity_date',
                'expected_date', 'state', 'locked', 'invoice_status', 'delivery_status', 'picking_policy', 'amount_untaxed', 'amount_tax', 'amount_total', 'amount_to_invoice', 'amount_invoiced',
                'amount_paid', 'amount_undiscounted', 'currency_id', 'currency_rate', 'client_order_ref', 'origin', 'reference', 'note', 'type_name', 'country_code', 'user_id', 'team_id',
                'warehouse_id', 'company_id', 'order_line', 'invoice_count', 'delivery_count', 'require_payment', 'require_signature', 'prepayment_percent', 'tag_ids', 'invoice_ids', 'picking_ids',
            ],
            'context' => ['lang' => config('services.odoo.language')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'date_order desc'], $params),
        ]];
        $response = $this->request($method, $url, $options);

        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getOrders', $url, $options, $response);
            return [];
        endif;
        return $response;
    }
    public function createOrder(array $data): array|int {
        $method = 'POST';
        $url = 'json/2/sale.order/create';
        $options = ['json' => [
            'vals_list' => $data,
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('error', 'BADREQUEST createOrder', $url, $options, $response);
            return [];
        else:
            $this->log('info', 'SUCCESS createOrder', $url, $options, $response);
        endif;
        return $response;
    }
    public function confirmOrder(int $orderId): mixed {
        $method = 'POST';
        $url = 'json/2/sale.order/action_confirm';
        $options = [
            'json' => [
                'ids' => [$orderId],
            ],
        ];
        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('error', 'BADREQUEST confirmOrder', $url, $options, $response);
            return [];
        endif;
        $this->log('info', 'SUCCESS confirmOrder', $url, $options, $response);
        return $response;
    }
}
