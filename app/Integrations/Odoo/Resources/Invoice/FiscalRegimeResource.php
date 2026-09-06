<?php

namespace App\Integrations\Odoo\Resources\Invoice;

use App\Integrations\Odoo\Client\Invoice\InvoiceClient;
use App\Integrations\Odoo\Dto\Invoice\FiscalRegimeDto;

class FiscalRegimeResource
{
    protected InvoiceClient $invoiceClient;

    public function __construct() {
        $this->invoiceClient = new InvoiceClient;
    }
    public function getAll(): array {
        $result = [];
        $rawRegimes = $this->invoiceClient->getFiscalRegimes();
        foreach ($rawRegimes as $regimeData) {
            $fiscalRegimeDto = FiscalRegimeDto::handle($regimeData);
            $result[$fiscalRegimeDto->code] = $fiscalRegimeDto->toArray();
        }

        return $result;
    }
}
