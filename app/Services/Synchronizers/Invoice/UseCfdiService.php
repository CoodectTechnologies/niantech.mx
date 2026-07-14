<?php

namespace App\Services\Synchronizers\Invoice;

use App\Models\UseCfdi;
use App\Services\Integrations\Odoo\Invoice\UseCfdiService as OdooUseCfdiService;
use Illuminate\Support\Facades\Log;
use Throwable;

class UseCfdiService
{
    protected OdooUseCfdiService $odooUseCfdiService;

    public function __construct() {
        $this->odooUseCfdiService = new OdooUseCfdiService;
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
            foreach ($this->odooUseCfdiService->getAll() as $useCfdi) {
                try {
                    $code = $useCfdi['code'];
                    if (! $code) {
                        $result['skipped'] += 1;
                        dd($useCfdi, $result);

                        continue;
                    }

                    $providerCodes[] = $code;
                    $description = $useCfdi['description'];
                    $localUseCfdi = UseCfdi::query()
                        ->where('code', $code)
                        ->first();

                    if ($localUseCfdi) {
                        $localUseCfdi->update([
                            'code' => $code,
                            'description' => $description,
                        ]);
                        $result['updated'] += 1;
                    } else {
                        UseCfdi::create([
                            'code' => $code,
                            'description' => $description,
                        ]);
                        $result['created'] += 1;
                    }
                } catch (Throwable $e) {
                    $result['failed'] += 1;
                    Log::channel('odoo.general')->error('Error syncing use cfdi: '.$e->getMessage(), [
                        'use_cfdi' => $useCfdi,
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ]);
                }
            }

            $query = UseCfdi::query();
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
