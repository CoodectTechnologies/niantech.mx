<?php

namespace App\Services\Integrations\Odoo\Invoice;

use App\DTO\Integrations\Odoo\Invoice\FiscalRegimeDTO;
use App\Integrations\Odoo;

class FiscalRegimeService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function getAll(): array {
        $result = [];
        $rawRegimes = $this->odoo->getFiscalRegimes();
        foreach ($rawRegimes as $regimeData) {
            $fiscalRegimeDTO = FiscalRegimeDTO::handle($regimeData);
            $result[$fiscalRegimeDTO->code] = $fiscalRegimeDTO->toArray();
        }

        return $result;
    }
}
