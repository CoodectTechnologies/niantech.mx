<?php

namespace Database\Seeders;

use App\Models\PlanFeature;
use Illuminate\Database\Seeder;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // PLAN BASICO
        PlanFeature::create(['name' => 'Feature basic - 1']);
        PlanFeature::create(['name' => 'Feature basic - 2']);

        // PLAN PREMIUM
        PlanFeature::create(['name' => 'Incluye **Todas las funciones** del Plan Básico']);
        PlanFeature::create(['name' => 'Feature premium - 1']);
        PlanFeature::create(['name' => 'Feature premium - 2']);
        PlanFeature::create(['name' => 'Feature premium - 3']);
        PlanFeature::create(['name' => 'Feature premium - 4']);
        PlanFeature::create(['name' => 'Feature premium - 5']);
        PlanFeature::create(['name' => 'Feature premium - 6']);
    }
}
