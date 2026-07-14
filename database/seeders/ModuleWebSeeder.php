<?php

namespace Database\Seeders;

use App\Models\ModuleWeb;
use Illuminate\Database\Seeder;

class ModuleWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $moduleWebs = [
            // Web
            ['name' => 'Inicio'],
            ['name' => 'Inicio - call to action'],
            ['name' => 'Nosotros'],
            ['name' => 'Servicios'],
            ['name' => 'Blog'],
            ['name' => 'Videos'],
            ['name' => 'Galeria'],
            ['name' => 'Contacto'],
            ['name' => 'Categorias'],
            ['name' => 'Productos'],
            ['name' => 'Productos sidebar'],
        ];
        ModuleWeb::insert($moduleWebs);
    }
}
