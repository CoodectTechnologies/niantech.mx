<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $basic = Plan::create([
            'order' => 1,
            'title' => 'Plan Básico',
            'subtitle' => 'Ideal para el miembro que busca su **presencia digital** y compartir fácilmente.',
            'stripe_id' => app()->isProduction() ? '' : 'prod_ToPlj0DLO8BEde',
            'stripe_product_name' => app()->isProduction() ? '' : 'COODECT',
            'stripe_price_month_id' => app()->isProduction() ? '' : 'price_1SqmvwHrnfAq7EbHRX5LLDss',
            'stripe_price_year_id' => app()->isProduction() ? '' : 'price_1SqmxRHrnfAq7EbHt89qd5Ie',
            'amount_month' => 100,
            'amount_year' => 720,
            'free_trial_days' => 14,
            'status' => true,
            'featured' => false,
        ]);
        $basic->planFeatures()->sync([1, 2]);
        $basic->givePermissionTo();

        $premium = Plan::create([
            'order' => 2,
            'title' => 'Plan Premium',
            'subtitle' => 'La solución completa para el miembro enfocado en métricas y eficiencia.',
            'stripe_id' => app()->isProduction() ? '' : 'prod_ToPlj0DLO8BEde',
            'stripe_product_name' => app()->isProduction() ? '' : 'COODECT',
            'stripe_price_month_id' => app()->isProduction() ? '' : 'price_1Sqn8BHrnfAq7EbHQVglcF2t',
            'stripe_price_year_id' => app()->isProduction() ? '' : 'price_1Sqn8WHrnfAq7EbHNMV7NnLX',
            'amount_month' => 150,
            'amount_year' => 1080,
            'free_trial_days' => 14,
            'status' => true,
            'featured' => true,
        ]);
        $premium->planFeatures()->sync([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $premium->givePermissionTo();

        $bussiness = Plan::create([
            'order' => 3,
            'title' => 'Plan Business',
            'subtitle' => 'Para usuarios que requieren un servicio más personalizado.',
            'stripe_id' => null,
            'stripe_product_name' => null,
            'stripe_price_month_id' => null,
            'stripe_price_year_id' => null,
            'amount_month' => 0,
            'amount_year' => 0,
            'free_trial_days' => 0,
            'status' => true,
            'featured' => false,
        ]);
    }
}
