<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Partners desde la carpeta brand
        $brandPath = 'assets/ecommerce/images/brands/';
        $publicPath = 'partner/';
        $files = [
            '1.png',
            '2.png',
            '3.png',
            '4.png',
            '5.png',
            '6.png',
            '7.png',
        ];
        foreach ($files as $file) {
            $name = pathinfo($file, PATHINFO_FILENAME);
            $partner = Partner::create([
                'name' => $name,
            ]);
            $partner->image()->create([
                'main' => 1,
                'url' => mediaManagerSeeder($brandPath.$file, $publicPath.$file),
            ]);
        }
        Partner::regenerateCache();
    }
}
