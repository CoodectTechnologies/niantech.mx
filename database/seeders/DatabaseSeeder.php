<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        // System
        $this->call(CountrySeeder::class);
        $this->call(StateSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ModuleWebSeeder::class);

        // Web
        $this->call(BannerSeeder::class);
        $this->call(AboutSeeder::class);
        $this->call(VideoSeeder::class);
        $this->call(BlogTagSeeder::class);
        $this->call(BlogCategorySeeder::class);
        $this->call(BlogPostSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(PortfolioSeeder::class);
        $this->call(PartnerSeeder::class);
        $this->call(TestimonySeeder::class);
        $this->call(NewsletterSeeder::class);
        $this->call(PackageFeatureSeeder::class);
        $this->call(PackageSeeder::class);
        // $this->call(QuestionnaireSeeder::class);
        // $this->call(ChatbotSeeder::class);
        $this->call(PlanFeatureSeeder::class);
        $this->call(PlanSeeder::class);

        // Ecommerce
        $this->call(CurrencySeeder::class);
        $this->call(ProductWarehouseSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductGenderSeeder::class);
        $this->call(ProductOptionSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(ShippingZoneSeeder::class);
        // $this->call(AddressSeeder::class);
        $this->call(OrderSeeder::class);
        // $this->call(ConfiguratorStageSeeder::class);
        // $this->call(ConfiguratorCompatibilitySeeder::class);
        // $this->call(ConfiguratorPerformanceSeeder::class);
        // $this->call(ConfiguratorGameSeeder::class);
        // $this->call(ConfiguratorChipsetSeeder::class);
        // $this->call(ConfiguratorBudgetSeeder::class);
        // $this->call(ConfiguratorBudgetProductSeeder::class);
        // $this->call(ConfiguratorFPSSeeder::class);
        $this->call(UseCfdiSeeder::class);
        $this->call(FiscalRegimeSeeder::class);
        $this->call(InvoiceMotiveCancelSeeder::class);
        $this->call(InvoiceFormPaymentSeeder::class);
        $this->call(UnitTypeSeeder::class);
    }
}
