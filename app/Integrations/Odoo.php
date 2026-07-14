<?php

namespace App\Integrations;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;

class Odoo
{
    public static $code = 'ODOO';
    protected Client $client;

    public function __construct() {
        $this->client = new Client([
            'base_uri' => config('services.odoo.url'),
            'verify' => app()->environment('production'),
            'cookies' => true,
            'headers' => [
                'Authorization' => 'Bearer '.config('services.odoo.key'),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Odoo-Database' => config('services.odoo.database'),
            ],
        ]);
    }

    /* ========================================================================= */
    /* LOCATION */
    /* ========================================================================= */
    public function getCountries(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.country/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([], $domain),
            'fields' => ['id', 'name', 'display_name', 'code', 'phone_code', 'state_ids'],
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'name asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getCountries', $url, $options, $response);

            return [];
        }

        return $response;
    }
    public function getStates(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/res.country.state/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([], $domain),
            'fields' => ['id', 'name', 'code', 'country_id'],
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'name asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getStates', $url, $options, $response);

            return [];
        }

        return $response;
    }

    /* ========================================================================= */
    /* INVOICES */
    /* ========================================================================= */
    public function getUseCfdis(): array {
        $method = 'POST';
        $url = 'json/2/res.partner/fields_get';
        $options = ['json' => [
            'allfields' => [
                'l10n_mx_edi_usage',
            ],
            'context' => ['lang' => config('translatable.fallback')],
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getUseCfdis', $url, $options, $response);

            return [];
        }

        return $response['l10n_mx_edi_usage']['selection'];
    }
    public function getFiscalRegimes(): array {
        $method = 'POST';
        $url = 'json/2/res.partner/fields_get';
        $options = ['json' => [
            'allfields' => [
                'l10n_mx_edi_fiscal_regime',
            ],
            'context' => ['lang' => config('translatable.fallback')],
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getUseCfdis', $url, $options, $response);

            return [];
        }

        return $response['l10n_mx_edi_fiscal_regime']['selection'];
    }

    /* ========================================================================= */
    /* PRODUCTS */
    /* ========================================================================= */
    public function getProducts(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/product.template/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['sale_ok', '=', true], ['active', '=', true]], $domain),
            'fields' => ['id', 'name', 'default_code', 'list_price', 'standard_price', 'currency_id', 'qty_available', 'virtual_available', 'description_sale', 'description', 'categ_id', 'barcode', 'weight', 'volume'],
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getProducts', $url, $options, $response);

            return [];
        }

        return $response;
    }
    public function getWarehouses(array $domain = [], array $params = []): array {
        $method = 'POST';
        $url = 'json/2/stock.quant/search_read';
        $options = ['json' => [
            'domain' => arrayMergeDeep([['quantity', '>', 0], ['location_id.usage', '=', 'internal']], $domain),
            'fields' => ['product_id', 'location_id', 'quantity', 'reserved_quantity'],
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200], $params),
        ]];
        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getWarehouses: ', $url, $options, $response);

            return [];
        }

        return $response;
    }

    /* ========================================================================= */
    /* CUSTOMERS */
    /* ========================================================================= */
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
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'id asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getCustomers', $url, $options, $response);

            return [];
        }

        return $response;
    }
    public function createCustomer(array $data): array|int {
        $method = 'POST';
        $url = 'json/2/res.partner/create';
        $options = ['json' => [
            'vals_list' => [array_merge($data, ['customer_rank' => 1])],
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST createCustomer', $url, $options, $response);

            return [];
        }

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
        if (isset($response['debug'])) {
            $this->log('BADREQUEST updateCustomer', $url, $options, $response);

            return false;
        }

        return true;
    }

    /* ========================================================================= */
    /* ADDRESSES */
    /* ========================================================================= */
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
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'id asc'], $params),
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getAddresses', $url, $options, $response);

            return [];
        }

        return $response;
    }
    public function createAddress(array $data): array|int {
        $method = 'POST';
        $url = 'json/2/res.partner/create';
        $options = ['json' => [
            'vals_list' => [$data],
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST createAddress', $url, $options, $response);

            return [];
        }

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
        if (isset($response['debug'])) {
            $this->log('BADREQUEST updateAddress', $url, $options, $response);

            return false;
        }

        return $response;
    }
    public function deleteAddress(int $addressId): bool {
        $method = 'POST';
        $url = 'json/2/res.partner/unlink';
        $options = ['json' => [
            'ids' => [$addressId],
        ]];

        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST deleteAddress', $url, $options, $response);

            return false;
        }

        return (bool) $response;
    }

    /* ========================================================================= */
    /* ORDERS */
    /* ========================================================================= */
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
            'context' => ['lang' => config('translatable.fallback')],
            ...array_merge(['offset' => 0, 'limit' => 200, 'order' => 'date_order desc'], $params),
        ]];
        $response = $this->request($method, $url, $options);
        if (isset($response['debug'])) {
            $this->log('BADREQUEST getOrders', $url, $options, $response);

            return [];
        }

        return $response;
    }

    /* ========================================================================= */
    /* REQUEST */
    /* ========================================================================= */
    private function request(string $method, string $url, array $options = []): array|string|bool {
        try {
            $response = $this->client->request($method, $url, $options);
            $result = json_decode($response->getBody()->getContents(), true);

            return $result ?? [];
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $result = json_decode($response->getBody(), true);
            $this->log('REQUESTEXCEPTION request: '.$e->getMessage(), $url, $options, $result, $e);

            return $result;
        } catch (Exception $e) {
            $this->log('EXCEPTION request: '.$e->getMessage(), $url, $options, [], $e);

            return [];
        } catch (Throwable $e) {
            $this->log('THROWABLE request: '.$e->getMessage(), $url, $options, [], $e);

            return [];
        }
    }

    /* ========================================================================= */
    /* LOG */
    /* ========================================================================= */
    private function log(string $title, string $url, array $data = [], array $response = [], ?Throwable $exception = null) {
        Log::channel('odoo.api')->info($title);
        Log::channel('odoo.api')->info(config('services.erp.url').$url);
        Log::channel('odoo.api')->info('data', $data);
        Log::channel('odoo.api')->info('response', (array) $response);
        if ($exception) {
            Log::channel('odoo.api')->info('exception', ['message' => $exception->getMessage(), 'file' => $exception->getFile(), 'line' => $exception->getLine()]);
        }
        Log::channel('odoo.api')->info('================================================================================');
    }
}
