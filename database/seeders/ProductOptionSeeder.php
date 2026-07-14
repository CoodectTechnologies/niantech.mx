<?php

namespace Database\Seeders;

use App\Models\ProductOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $commonOptions = [
            'Color',
            'Talla',
            'Medida',
            'Material',
            'Tamaño',
            'Estilo',
            'Acabado',
            'Peso',
            'Capacidad',
            'Voltaje',
        ];

        foreach ($commonOptions as $name) {
            ProductOption::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
