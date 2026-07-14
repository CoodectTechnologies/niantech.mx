<?php

namespace App\Services\Integrations\Odoo\Invoice;

use App\DTO\Integrations\Odoo\Invoice\UseCfdiDTO;
use App\Integrations\Odoo;

class UseCfdiService
{
    protected Odoo $odoo;

    public function __construct() {
        $this->odoo = new Odoo;
    }
    public function getAll(): array {
        $result = [];
        $rawUseCfdis = $this->odoo->getUseCfdis();
        foreach ($rawUseCfdis as $useCfdiData) {
            $useCfdiDto = UseCfdiDTO::handle($useCfdiData);
            $result[$useCfdiDto->code] = $useCfdiDto->toArray();
        }

        return $result;
    }
}
