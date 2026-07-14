<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $about = [
            'title' => [config('translatable.fallback') => 'Servicio responsable, profesional <span>y transparente.</span>'],
            'information' => [config('translatable.fallback') => 'Desde su fundación, esta notaría se ha distinguido por hacer las cosas bien: con responsabilidad, cumplimiento y total transparencia. Nos comprometemos con cada cliente como si fuera el único, atendiendo con seriedad todos los asuntos, sin importar su complejidad'],
            'mission' => [config('translatable.fallback') => 'Brindar servicios notariales que ofrezcan certeza jurídica mediante atención profesional, trato digno y soluciones claras que protejan los intereses de nuestros clientes.'],
            'vision' => [config('translatable.fallback') => 'Consolidar una notaría moderna, confiable y cercana, reconocida por su transparencia, calidad jurídica y continuidad institucional para las generaciones futuras.'],
            'values' => [config('translatable.fallback') => 'Responsabilidad, Trato humano, Profesionalismo, Compromiso ético'],
        ];
        $about = About::create($about);
        $about->image()->create([
            'url' => mediaManagerSeeder('assets/web/images/resources/about-two-img-1.jpg', 'about/main-1.jpg'),
            'main' => 1,
        ]);
        $about->image2()->create([
            'url' => mediaManagerSeeder('assets/web/images/resources/about-two-img-2.jpg', 'about/main-2.jpg'),
            'name' => 'image2',
            'main' => 1,
        ]);
        About::regenerateCache();
    }
}
