<?php

namespace App\Integrations\Odoo\Resources\Invoice;

use App\Integrations\Odoo\Client\Invoice\InvoiceClient;
use App\Integrations\Odoo\Dto\Invoice\UseCfdiDto;

class UseCfdiResource
{
    protected InvoiceClient $invoiceClient;

    public function __construct() {
        $this->invoiceClient = new InvoiceClient;
    }
    public function getAll(): array {
        $result = [];
        $rawUseCfdis = $this->invoiceClient->getUseCfdis();
        foreach ($rawUseCfdis as $useCfdiData) {
            $useCfdiDto = UseCfdiDto::handle($useCfdiData);
            $result[$useCfdiDto->code] = $useCfdiDto->toArray();
        }

        return $result;
    }
}
