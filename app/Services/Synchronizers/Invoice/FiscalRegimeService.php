<?php

namespace App\Services\Synchronizers\Invoice;

use App\Models\FiscalRegime;
use App\Services\Integrations\Odoo\Invoice\FiscalRegimeService as OdooFiscalRegimeService;
use Illuminate\Support\Facades\Log;
use Throwable;

class FiscalRegimeService
{
    protected OdooFiscalRegimeService $odooFiscalRegimeService;

    public function __construct() {
        $this->odooFiscalRegimeService = new OdooFiscalRegimeService;
    }
    public function save(): array {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'created' => 0,
                'updated' => 0,
                'deleted' => 0,
                'skipped' => 0,
                'failed' => 0,
            ];

            if (! config('services.odoo.status')) {
                $result['time'] = microtime(true) - $startTime;

                return $result;
            }

            $providerCodes = [];
            foreach ($this->odooFiscalRegimeService->getAll() as $fiscalRegime) {
                try {
                    $code = $fiscalRegime['code'];
                    if (! $code) {
                        $result['skipped'] += 1;

                        continue;
                    }

                    $providerCodes[] = $code;
                    $description = $fiscalRegime['description'];
                    $localFiscalRegime = FiscalRegime::query()
                        ->where(function ($query) use ($code) {
                            $query->where('code', $code);
                        })
                        ->first();

                    if ($localFiscalRegime) {
                        $localFiscalRegime->update([
                            'code' => $code,
                            'description' => $description,
                        ]);
                        $result['updated'] += 1;
                    } else {
                        FiscalRegime::create([
                            'code' => $code,
                            'description' => $description,
                        ]);
                        $result['created'] += 1;
                    }
                } catch (Throwable $e) {
                    $result['failed'] += 1;
                    Log::channel('odoo.general')->error('Error syncing fiscal regime: '.$e->getMessage(), [
                        'fiscal_regime' => $fiscalRegime,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }

            $query = FiscalRegime::query();
            if ($providerCodes) {
                $query->whereNotIn('code', $providerCodes);
            }
            $deleted = $query->count();
            if ($deleted) {
                $query->delete();
                $result['deleted'] += $deleted;
            }

            $result['time'] = microtime(true) - $startTime;

            return $result;
        });
    }
}
