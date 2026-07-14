<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // APP OWN
        // $banner = Banner::create([
        //     'order' => 1,
        //     'module_web_id' => 2,
        //     'type' => 'Imagen',
        // ]);
        // $banner->image()->create([
        //     'url' => mediaManagerSeeder('assets/ecommerce/images/home/bienvenida-bg.jpg', 'banner/banner1.jpg'),
        //     'main' => 1,
        // ]);

        $banner = Banner::create([
            'order' => 2,
            'module_web_id' => 1,
            'type' => 'Imagen',
            'title' => [config('translatable.fallback') => 'Tecnología que impulsa tu espacio'],
            'subtitle' => [config('translatable.fallback') => 'TECNOLOGÍA PARA EMPRESAS'],
            'description' => [config('translatable.fallback') => 'El equipamiento profesiona, hardware, software, y accesorios para oficina y hogar'],
            'btn_text' => [config('translatable.fallback') => 'Ver catálogo'],
            'btn_url' => '/productos',
            'overlay' => false,
        ]);
        $banner->image()->create([
            'url' => mediaManagerSeeder('assets/ecommerce/images/home/banner.png', 'banner/banner2.png'),
            'main' => 1,
        ]);

        $banner = Banner::create([
            'order' => 3,
            'module_web_id' => 1,
            'type' => 'Imagen',
        ]);
        $banner->image()->create([
            'url' => mediaManagerSeeder('assets/ecommerce/images/home/sw_banner_gabinetes_hyte_touch.png', 'banner/banner3.png'),
            'main' => 1,
        ]);

        $banner = Banner::create([
            'order' => 4,
            'module_web_id' => 1,
            'type' => 'Imagen',
        ]);
        $banner->image()->create([
            'url' => mediaManagerSeeder('assets/ecommerce/images/home/sw_amd_bundle.png', 'banner/banner4.png'),
            'main' => 1,
        ]);

        $banner = Banner::create([
            'order' => 1,
            'module_web_id' => 2,
            'type' => 'Imagen',
            'title' => [config('translatable.fallback') => 'Soluciones tecnológicas para tu empresa'],
            'subtitle' => [config('translatable.fallback') => 'SOLUCIONES EMPRESARIALES'],
            'description' => [config('translatable.fallback') => 'Equipamiento de oficinas, Software empresarial, Infraestructura tecnológica, Soporte especializado'],
            'btn_text' => [config('translatable.fallback') => 'Solicitar cotización'],
            'btn_url' => '/contacto',
            'overlay' => false,
        ]);
        $banner->image()->create([
            'url' => mediaManagerSeeder('assets/ecommerce/images/home/call-to-action.png', 'banner/banner5.png'),
            'main' => 1,
        ]);

        // APP OTHERS
        // $banner = Banner::create([
        //     'module_web_id' => 1,
        //     'order' => 1,
        //     'title' => [config('translatable.fallback') => 'Servicio legal confiable, <br> <span>transparente y responsable</span>'],
        //     'subtitle' => [config('translatable.fallback') => 'Entendemos que cada trámite representa la tranquilidad, el patrimonio y el futuro de una persona o familia.'],
        //     'btn_url' => '#services',
        //     'btn_text' => [config('translatable.fallback') => 'Ver servicios'],
        //     'type' => 'Imagen',
        //     'color' => '#FFFFFF',
        // ]);
        // $banner->image()->create([
        //     'url' => mediaManagerSeeder('assets/web/images/backgrounds/slider-2-1.jpg', 'banner/banner1.jpg'),
        //     'main' => 1,
        // ]);

        // $banner = Banner::create([
        //     'module_web_id' => 1,
        //     'order' => 2,
        //     'title' => [config('translatable.fallback') => 'Compromiso ético <br> <span>y profesionalismo</span>'],
        //     'subtitle' => [config('translatable.fallback') => 'No somos diferentes por lo que hacemos, sino por la forma en la que lo hacemos.'],
        //     'btn_url' => '#about',
        //     'btn_text' => [config('translatable.fallback') => 'Conocenos'],
        //     'type' => 'Imagen',
        //     'color' => '#FFFFFF',
        // ]);
        // $banner->image()->create([
        //     'url' => mediaManagerSeeder('assets/web/images/backgrounds/slider-2-2.jpg', 'banner/banner2.jpg'),
        //     'main' => 1,
        // ]);

        Banner::regenerateCache();
    }
}
