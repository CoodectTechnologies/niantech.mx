<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // SYSTEM
        Permission::create(['name' => 'panel', 'alias' => [config('translatable.fallback') => 'Acceso al panel']]);
        Permission::create(['name' => 'usuarios', 'alias' => [config('translatable.fallback') => 'Usuarios']]);
        Permission::create(['name' => 'roles', 'alias' => [config('translatable.fallback') => 'Roles']]);
        Permission::create(['name' => 'permisos', 'alias' => [config('translatable.fallback') => 'Permisos']]);
        Permission::create(['name' => 'logs', 'alias' => [config('translatable.fallback') => 'Logs']]);
        Permission::create(['name' => 'backups', 'alias' => [config('translatable.fallback') => 'Backups']]);
        Permission::create(['name' => 'módulos web', 'alias' => [config('translatable.fallback') => 'Módulos Web']]);
        Permission::create(['name' => 'comentarios', 'alias' => [config('translatable.fallback') => 'Comentarios']]);
        Permission::create(['name' => 'generales', 'alias' => [config('translatable.fallback') => 'Generales']]);
        Permission::create(['name' => 'pulse', 'alias' => [config('translatable.fallback') => 'Pulse']]);
        Permission::create(['name' => 'subscripciones', 'alias' => [config('translatable.fallback') => 'Subscripciones']]);
        Permission::create(['name' => 'notificaciones', 'alias' => [config('translatable.fallback') => 'Notificaciones']]);

        // WEB
        Permission::create(['name' => 'banners', 'alias' => [config('translatable.fallback') => 'Banners']]);
        Permission::create(['name' => 'galería', 'alias' => [config('translatable.fallback') => 'Galería']]);
        Permission::create(['name' => 'nosotros', 'alias' => [config('translatable.fallback') => 'Nosotros']]);
        Permission::create(['name' => 'team', 'alias' => [config('translatable.fallback') => 'Team']]);
        Permission::create(['name' => 'videos', 'alias' => [config('translatable.fallback') => 'Videos']]);
        Permission::create(['name' => 'servicios', 'alias' => [config('translatable.fallback') => 'Servicios']]);
        Permission::create(['name' => 'portafolio', 'alias' => [config('translatable.fallback') => 'Portafolio']]);
        Permission::create(['name' => 'socios', 'alias' => [config('translatable.fallback') => 'Socios']]);
        Permission::create(['name' => 'blog', 'alias' => [config('translatable.fallback') => 'Blog']]);
        Permission::create(['name' => 'blog categorías', 'alias' => [config('translatable.fallback') => 'Blog Categorías']]);
        Permission::create(['name' => 'blog etiquetas', 'alias' => [config('translatable.fallback') => 'Blog Etiquetas']]);
        Permission::create(['name' => 'correos', 'alias' => [config('translatable.fallback') => 'Correos']]);
        Permission::create(['name' => 'testimonios', 'alias' => [config('translatable.fallback') => 'Testimonios']]);
        Permission::create(['name' => 'paquetes', 'alias' => [config('translatable.fallback') => 'Paquetes']]);
        Permission::create(['name' => 'paquetes características', 'alias' => [config('translatable.fallback') => 'Paquetes Características']]);
        Permission::create(['name' => 'preguntas y respuestas', 'alias' => [config('translatable.fallback') => 'Preguntas y Respuestas']]);
        Permission::create(['name' => 'newsletter', 'alias' => [config('translatable.fallback') => 'Newsletter']]);
        Permission::create(['name' => 'contacto', 'alias' => [config('translatable.fallback') => 'Contacto']]);
        Permission::create(['name' => 'etiquetas analíticas', 'alias' => [config('translatable.fallback') => 'Etiquetas Analíticas']]);
        Permission::create(['name' => 'accesos mailchimp', 'alias' => [config('translatable.fallback') => 'Accesos Mailchimp']]);
        Permission::create(['name' => 'accesos captcha', 'alias' => [config('translatable.fallback') => 'Accesos Captcha']]);
        Permission::create(['name' => 'accesos google', 'alias' => [config('translatable.fallback') => 'Accesos Google']]);
        Permission::create(['name' => 'avisos de privacidad', 'alias' => [config('translatable.fallback') => 'Avisos de Privacidad']]);
        Permission::create(['name' => 'cuestionarios', 'alias' => [config('translatable.fallback') => 'Cuestionarios']]);
        Permission::create(['name' => 'chatbot', 'alias' => [config('translatable.fallback') => 'Chatbot']]);
        Permission::create(['name' => 'billing', 'alias' => [config('translatable.fallback') => 'Billing']]);

        // ECOMMERCE
        Permission::create(['name' => 'analytic search', 'alias' => [config('translatable.fallback') => 'Analytic Search']]);
        Permission::create(['name' => 'integraciones', 'alias' => [config('translatable.fallback') => 'Integraciones']]);
        Permission::create(['name' => 'ordenes', 'alias' => [config('translatable.fallback') => 'Órdenes']]);
        Permission::create(['name' => 'productos', 'alias' => [config('translatable.fallback') => 'Productos']]);
        Permission::create(['name' => 'producto almacenes', 'alias' => [config('translatable.fallback') => 'Producto Almacenes']]);
        Permission::create(['name' => 'producto categorías', 'alias' => [config('translatable.fallback') => 'Producto Categorías']]);
        Permission::create(['name' => 'producto marcas', 'alias' => [config('translatable.fallback') => 'Producto Marcas']]);
        Permission::create(['name' => 'producto géneros', 'alias' => [config('translatable.fallback') => 'Producto Géneros']]);
        Permission::create(['name' => 'mayoreo', 'alias' => [config('translatable.fallback') => 'Mayoreo']]);
        Permission::create(['name' => 'promociones', 'alias' => [config('translatable.fallback') => 'Promociones']]);
        Permission::create(['name' => 'cupones', 'alias' => [config('translatable.fallback') => 'Cupones']]);
        Permission::create(['name' => 'países', 'alias' => [config('translatable.fallback') => 'Países']]);
        Permission::create(['name' => 'estados', 'alias' => [config('translatable.fallback') => 'Estados']]);
        Permission::create(['name' => 'zonas de envío', 'alias' => [config('translatable.fallback') => 'Zonas de Envío']]);
        Permission::create(['name' => 'clases de envío', 'alias' => [config('translatable.fallback') => 'Clases de Envío']]);
        Permission::create(['name' => 'monedas', 'alias' => [config('translatable.fallback') => 'Monedas']]);
        Permission::create(['name' => 'pasarelas de pago', 'alias' => [config('translatable.fallback') => 'Pasarelas de Pago']]);
        Permission::create(['name' => 'popup', 'alias' => [config('translatable.fallback') => 'Popup']]);
        Permission::create(['name' => 'configurador', 'alias' => [config('translatable.fallback') => 'Configurador']]);
        Permission::create(['name' => 'configurador pasos', 'alias' => [config('translatable.fallback') => 'Configurador Pasos']]);
        Permission::create(['name' => 'configurador compatibilidades', 'alias' => [config('translatable.fallback') => 'Configurador Compatibilidades']]);
        Permission::create(['name' => 'configurador rendimiento', 'alias' => [config('translatable.fallback') => 'Configurador Rendimiento']]);
        Permission::create(['name' => 'configurador juegos', 'alias' => [config('translatable.fallback') => 'Configurador Juegos']]);
        Permission::create(['name' => 'configurador rangos', 'alias' => [config('translatable.fallback') => 'Configurador Rangos']]);
        Permission::create(['name' => 'configurador fps', 'alias' => [config('translatable.fallback') => 'Configurador FPS']]);
        Permission::create(['name' => 'facturas', 'alias' => [config('translatable.fallback') => 'Facturas']]);
        Permission::create(['name' => 'facturas regimenes', 'alias' => [config('translatable.fallback') => 'Facturas Regímenes']]);
        Permission::create(['name' => 'facturas usos de cfdi', 'alias' => [config('translatable.fallback') => 'Facturas Usos de CFDI']]);
        Permission::create(['name' => 'facturas credenciales fiel', 'alias' => [config('translatable.fallback') => 'Facturas Credenciales FIEL']]);
        Permission::create(['name' => 'facturas sw sapien', 'alias' => [config('translatable.fallback') => 'Facturas SW Sapien']]);
        Permission::create(['name' => 'tipos de unidades', 'alias' => [config('translatable.fallback') => 'Tipos de Unidades']]);
        Permission::create(['name' => 'proveedor odoo', 'alias' => [config('translatable.fallback') => 'Proveedor Odoo']]);
        Permission::create(['name' => 'proveedor vadeto brands', 'alias' => [config('translatable.fallback') => 'proveedor vadeto brands']]);
    }
}
