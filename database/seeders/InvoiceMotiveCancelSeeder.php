<?php

namespace Database\Seeders;

use App\Models\InvoiceMotiveCancel;
use Illuminate\Database\Seeder;

class InvoiceMotiveCancelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $motives = [
            ['code' => '01', 'description' => 'Comprobante emitido con errores con relación'],
            ['code' => '02', 'description' => 'Comprobante emitido con errores sin relación'],
            ['code' => '03', 'description' => 'No se llevó a cabo la operación'],
            ['code' => '04', 'description' => 'Operación nominativa relacionada en la factura global'],
        ];
        foreach ($motives as $motive) {
            InvoiceMotiveCancel::create($motive);
        }
    }
}
