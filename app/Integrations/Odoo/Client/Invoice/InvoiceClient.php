<?php

namespace App\Integrations\Odoo\Client\Invoice;

use App\Integrations\Odoo\Client\OdooClient;

class InvoiceClient extends OdooClient
{    
    public function getUseCfdis(): array {
        $method = 'POST';
        $url = 'json/2/res.partner/fields_get';
        $options = ['json' => [
            'allfields' => [
                'l10n_mx_edi_usage',
            ],
            'context' => ['lang' => config('services.odoo.language')],
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getUseCfdis', $url, $options, $response);
            return [];
        endif;
        return $response['l10n_mx_edi_usage']['selection'];
    }
    public function getFiscalRegimes(): array {
        $method = 'POST';
        $url = 'json/2/res.partner/fields_get';
        $options = ['json' => [
            'allfields' => [
                'l10n_mx_edi_fiscal_regime',
            ],
            'context' => ['lang' => config('services.odoo.language')],
        ]];

        $response = $this->request($method, $url, $options);
        if(isset($response['debug'])):
            $this->log('warning', 'BADREQUEST getUseCfdis', $url, $options, $response);
            return [];
        endif;
        return $response['l10n_mx_edi_fiscal_regime']['selection'];
    }
}
