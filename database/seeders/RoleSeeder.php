<?php

namespace Database\Seeders;

use App\Enums\Role\Role as EnumsRole;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Roles
        $administrador = Role::create(['name' => EnumsRole::ADMINISTRATOR->value]);
        $copywriter = Role::create(['name' => EnumsRole::COPYWRITER->value]);
        $ecommerceManager = Role::create(['name' => EnumsRole::ECOMMERCE->value]);
        $webManager = Role::create(['name' => EnumsRole::WEB->value]);
        $client = Role::create(['name' => EnumsRole::CLIENT->value]);

        // Assing permissions
        $administrador->givePermissionTo(
            Permission::all()->pluck('name')->toArray()
        );
        $copywriter->givePermissionTo([
            'panel',
            'blog',
            'blog categorías',
            'blog etiquetas',
            'notificaciones',
        ]);
        $ecommerceManager->givePermissionTo([
            'panel',
            'banners',
            'ordenes',
            'productos',
            'producto categorías',
            'producto marcas',
            'producto géneros',
            'mayoreo',
            'promociones',
            'cupones',
            'comentarios',
            'países',
            'estados',
            'zonas de envío',
            'clases de envío',
            'notificaciones',
        ]);
        $webManager->givePermissionTo([
            'panel',
            'banners',
            'galería',
            'nosotros',
            'team',
            'videos',
            'servicios',
            'portafolio',
            'socios',
            'blog',
            'blog categorías',
            'blog etiquetas',
            'correos',
            'testimonios',
            'paquetes',
            'paquetes características',
            'preguntas y respuestas',
            'newsletter',
            'contacto',
            'etiquetas analíticas',
            'accesos mailchimp',
            'accesos captcha',
            'accesos google',
            'avisos de privacidad',
            'chatbot',
            'billing',
            'cuestionarios',
            'notificaciones',
        ]);
        $client->givePermissionTo([
            'billing',
            'notificaciones',
        ]);
    }
}
