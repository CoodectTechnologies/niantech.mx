<?php

namespace Database\Seeders;

use App\Models\ProductWarehouse;
use Illuminate\Database\Seeder;

class ProductWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        ProductWarehouse::create([
            'name' => 'Default',
        ]);
    }
}
